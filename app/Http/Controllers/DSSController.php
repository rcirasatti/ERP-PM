<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Material;
use App\Models\ItemPenawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DSSController extends Controller
{
    /**
     * Analisis penawaran dengan prediksi AI
     * 
     * Input: 
     * - penawaran_id: ID penawaran yang sudah di-draft
     * - grand_total: Nilai grand total dari penawaran (dari Excel BoQ)
     * 
     * Output:
     * - ai_prediksi_lr: Prediksi Linear Regression
     * - ai_prediksi_ma: Prediksi Moving Average
     * - margin_status: 'aman' atau 'overrun'
     * - ai_notes: Penjelasan rekomendasi
     * - risk_level: 'low', 'medium', 'high'
     */
    public function analyzePenawaran(Request $request)
    {
        $validated = $request->validate([
            'penawaran_id' => 'required|exists:penawaran,id',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $penawaran = Penawaran::with('items', 'client')->find($validated['penawaran_id']);

        try {
            // Get historical data untuk training ML
            $historicalData = $this->getHistoricalCostData();

            // Call Python API (skeleton) untuk prediksi
            $prediction = $this->callPythonDSSAPI([
                'penawaran_id' => $penawaran->id,
                'grand_total' => $validated['grand_total'],
                'client_id' => $penawaran->client_id,
                'items_count' => $penawaran->items->count(),
                'historical_data' => $historicalData,
                'wilayah' => $penawaran->wilayah,
                'jenis_pekerjaan' => $penawaran->jenis_pekerjaan,
            ]);

            // Update penawaran dengan hasil prediksi
            $penawaran->update([
                'ai_status' => 'analyzed',
                'ai_prediksi_lr' => $prediction['prediksi'], // Saved to existing column
                'margin_status' => $prediction['margin_status'],
                'ai_notes' => $prediction['ai_notes'],
            ]);

            // Calculate complexity score
            $complexityScore = $this->calculateComplexityScore([
                'items_count' => $penawaran->items->count(),
                'historical_data' => $historicalData,
            ]);

            // Get historical overrun rate
            $historicalOverrunRate = $this->getHistoricalOverrunRate([]);

            return response()->json([
                'success' => true,
                'message' => 'Analisis DSS selesai',
                'data' => [
                    'penawaran_id' => $penawaran->id,
                    'no_penawaran' => $penawaran->no_penawaran,
                    'grand_total' => (float) $validated['grand_total'],
                    'item_count' => $penawaran->items->count(),
                    'complexity_score' => (float) $complexityScore,
                    'historical_overrun_rate' => (float) $historicalOverrunRate * 100,
                    'ai_prediksi_lr' => (float) $prediction['prediksi'],
                    'margin_status' => $prediction['margin_status'],
                    'risk_level' => $prediction['risk_level'],
                    'estimated_actual_cost' => (float) $prediction['estimated_actual_cost'],
                    'estimated_overrun_percent' => (float) $prediction['estimated_overrun_percent'],
                    'ai_notes' => $prediction['ai_notes'],
                    'recommendation' => $prediction['recommendation'],
                    'predictions' => [
                        'machine_learning' => (float) $prediction['prediksi'],
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error dalam analisis: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve penawaran setelah review hasil DSS
     * Perubahan status: analyzed → approved
     * CRITICAL FIX: Add transaction wrapper and material validation
     */
    public function approvePenawaran(Request $request)
    {
        try {
            $validated = $request->validate([
                'penawaran_id' => 'required|exists:penawaran,id',
                'user_decision' => 'required|in:approve,reject,revise',
                'notes' => 'nullable|string',
            ]);

            $penawaran = Penawaran::with('items')->find($validated['penawaran_id']);

            if ($penawaran->ai_status !== 'analyzed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Penawaran belum di-analisis. Jalankan analisis terlebih dahulu.',
                ], 400);
            }

            // Initialize materialResult array
            $materialResult = [
                'created' => 0,
                'skipped' => 0,
                'errors' => [],
                'materials' => []
            ];

            // CRITICAL FIX #3: Wrap entire approval flow in transaction
            // This ensures data consistency if any step fails
            $result = DB::transaction(function () use ($penawaran, $validated, &$materialResult) {
                
                // CRITICAL FIX #2: Validate all items have material_id before approval
                if ($validated['user_decision'] === 'approve') {
                    $itemsWithoutMaterial = $penawaran->items->whereNull('material_id')->count();
                    
                    if ($itemsWithoutMaterial > 0) {
                        throw new \Exception(
                            "Tidak dapat approve. Masih ada {$itemsWithoutMaterial} item yang belum memiliki material. "
                            . "Jalankan material creation terlebih dahulu atau assign materials secara manual."
                        );
                    }
                    
                    // Try to create any materials from BoQ items if needed
                    $materialResult = $this->createMaterialsFromBoQItems($penawaran);
                    
                    if (count($materialResult['errors']) > 0) {
                        throw new \Exception(
                            "Material creation failed: " . implode(", ", array_slice($materialResult['errors'], 0, 3))
                        );
                    }
                }
                
                // User decision (manajer/direktur)
                if ($validated['user_decision'] === 'approve') {
                    $penawaran->update([
                        'status' => 'disetujui',
                        'ai_status' => 'approved',
                    ]);
                    
                    $message = 'Penawaran disetujui dan berlanjut ke tahap proyek.';
                    if ($materialResult['created'] > 0) {
                        $message .= ' ' . $materialResult['created'] . ' material baru telah dibuat otomatis.';
                    }
                    
                } elseif ($validated['user_decision'] === 'reject') {
                    $penawaran->update([
                        'status' => 'ditolak',
                        'ai_status' => 'approved',
                    ]);
                    
                    $message = 'Penawaran ditolak berdasarkan rekomendasi DSS.';
                    
                } else {
                    // 'revise' - reset ai_status dan create materials if needed
                    $materialResult = $this->createMaterialsFromBoQItems($penawaran);
                    
                    $penawaran->update([
                        'ai_status' => 'pending', // Reset untuk dianalisis ulang
                    ]);
                    
                    $message = 'Penawaran dikembalikan untuk revisi.';
                    if ($materialResult['created'] > 0) {
                        $message .= ' ' . $materialResult['created'] . ' material siap untuk digunakan.';
                    }
                }
                
                // ✅ CRITICAL FIX #1: Log DSS decision with full audit trail
                \Log::channel('dss_decisions')->info('DSS Decision Recorded', [
                    'penawaran_id' => $penawaran->id,
                    'no_penawaran' => $penawaran->no_penawaran,
                    'user_id' => auth()->id(),
                    'user_email' => auth()?->user()?->email,
                    'decision' => $validated['user_decision'],
                    'status_before' => 'analyzed',
                    'status_after' => $penawaran->status,
                    'risk_level' => $penawaran->margin_status,
                    'ai_prediction_lr' => (float) $penawaran->ai_prediksi_lr,
                    'ai_notes' => $penawaran->ai_notes,
                    'user_notes' => $validated['notes'] ?? null,
                    'materials_created' => $materialResult['created'],
                    'materials_errors' => $materialResult['errors'],
                    'timestamp' => now(),
                    'ip_address' => request()->ip(),
                ]);
                
                return [
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'penawaran_id' => $penawaran->id,
                        'status' => $penawaran->status,
                        'ai_status' => $penawaran->ai_status,
                        'materials_created' => ($validated['user_decision'] === 'approve' || $validated['user_decision'] === 'revise') ? $materialResult['created'] : 0,
                        'materials_info' => ($validated['user_decision'] === 'approve' || $validated['user_decision'] === 'revise') ? $materialResult : null,
                    ]
                ];
            });
            
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PRIVATE METHODS
     */

    /**
     * Get historical cost data from completed projects
     * Ini data yang digunakan untuk training model ML
     */
    private function getHistoricalCostData()
    {
        // Query: Ambil proyek yang sudah selesai dengan perbandingan penawaran vs pengeluaran aktual
        $completedProjects = DB::table('penawaran as p')
            ->join('proyek as pr', 'p.id', '=', 'pr.penawaran_id')
            ->leftJoin('pengeluaran as pg', 'pr.id', '=', 'pg.proyek_id')
            ->where('pr.status', 'selesai')
            ->select(
                'p.id as penawaran_id',
                'pr.id as proyek_id',
                'p.grand_total_with_ppn as quoted_cost',
                DB::raw('COALESCE(SUM(pg.jumlah), 0) as actual_cost'),
                DB::raw('COUNT(DISTINCT pg.id) as expense_count')
            )
            ->groupBy(['p.id', 'pr.id', 'p.grand_total_with_ppn'])
            ->having('actual_cost', '>', 0)
            ->get();

        $data = [];
        foreach ($completedProjects as $project) {
            $variance = $project->actual_cost - $project->quoted_cost;
            $variance_percent = ($variance / $project->quoted_cost) * 100;

            $data[] = [
                'penawaran_id' => $project->penawaran_id,
                'quoted_cost' => $project->quoted_cost,
                'actual_cost' => $project->actual_cost,
                'variance' => $variance,
                'variance_percent' => $variance_percent,
                'overrun' => $variance > 0,
            ];
        }

        return $data;
    }

    /**
     * Call Python DSS API untuk prediksi biaya
     * 
     * SKELETON: Saat ini return hardcoded value
     * Di masa depan: call ke Python Flask/FastAPI dengan ML model
     */
    private function callPythonDSSAPI($data)
    {
        $quotedCost = $data['grand_total'];
        
        // Execute Python ML Script
        $pythonScriptPath = storage_path('app/ml/predict_dss.py');
        $pythonExecutable = storage_path('app/ml/venv/Scripts/python.exe');
        
        // Buat input JSON
        $inputData = json_encode([
            'jenis_pekerjaan' => $data['jenis_pekerjaan'] ?? 'Project / Purchase Order',
            'wilayah' => $data['wilayah'] ?? 'Kota Semarang',
            'grand_total' => $quotedCost
        ]);
        
        // Eksekusi script menggunakan Symfony Process untuk error handling lebih baik
        $process = new \Symfony\Component\Process\Process([$pythonExecutable, $pythonScriptPath, $inputData]);
        $process->run();
        
        $outputStr = '';
        if (!$process->isSuccessful()) {
            $outputStr = $process->getErrorOutput();
            \Log::error("Python ML Process Failed in DSSController: " . $outputStr);
            $result = ['success' => false, 'error' => $outputStr];
        } else {
            $outputStr = $process->getOutput();
            $result = json_decode($outputStr, true);
        }
        
        // Prediksi Machine Learning
        if (isset($result['success']) && $result['success']) {
            $prediksi = $result['prediction'];
        } else {
            // Fallback ke quotedCost (0% overrun) jika script gagal/error
            $prediksi = $quotedCost;
            \Log::error("Python ML Error: " . ($result['error'] ?? 'Unknown Error'), ['output' => $outputStr]);
        }
        
        $estimatedActualCost = $prediksi;
        $overrunPercent = (($estimatedActualCost - $quotedCost) / $quotedCost) * 100;
        
        // === 100% ML Driven Risk Assessment ===
        // Sesuai dengan hasil model prediksi: Jika Prediksi Biaya Aktual > Penawaran = Overrun / Rugi
        
        if ($estimatedActualCost > $quotedCost) {
            $marginStatus = 'overrun';
            if ($overrunPercent > 10) {
                $riskLevel = 'high';
                $recommendation = '🔴 DANGER: Prediksi biaya aktual (Rp ' . number_format($estimatedActualCost, 0, ',', '.') . ') melebihi nilai penawaran. Potensi kerugian besar sebesar ' . round($overrunPercent, 1) . '%. TIDAK DIREKOMENDASIKAN.';
            } else {
                $riskLevel = 'medium';
                $recommendation = '⚠️ WARNING: Prediksi biaya aktual (Rp ' . number_format($estimatedActualCost, 0, ',', '.') . ') sedikit melebihi nilai penawaran. Potensi overrun ' . round($overrunPercent, 1) . '%. Pertimbangkan revisi.';
            }
        } else {
            $marginStatus = 'aman';
            $riskLevel = 'low';
            $recommendation = '✅ AMAN: Prediksi biaya aktual (Rp ' . number_format($estimatedActualCost, 0, ',', '.') . ') masih dalam batas nilai penawaran. Risiko rendah, dapat dilanjutkan.';
        }

        $notes = "Analisis AI: Prediksi biaya aktual Rp " . number_format($estimatedActualCost, 0, ',', '.') . " (" . ($overrunPercent > 0 ? "+" : "") . round($overrunPercent, 1) . "% variance dari penawaran).";

        return [
            'prediksi' => $prediksi,
            'estimated_actual_cost' => $estimatedActualCost,
            'estimated_overrun_percent' => $overrunPercent,
            'margin_status' => $marginStatus,
            'risk_level' => $riskLevel,
            'ai_notes' => $notes,
            'recommendation' => $recommendation,
        ];
    }

    /**
     * Calculate risk factor based on project characteristics
     * Higher = more risky = higher chance of overrun
     */
    private function calculateRiskFactor($data)
    {
        $riskFactor = 0;

        // Risk factor: banyak items = kompleks = riskier
        if ($data['items_count'] > 10) {
            $riskFactor += 0.3;
        } elseif ($data['items_count'] > 5) {
            $riskFactor += 0.15;
        }

        // Risk factor: nilai besar = riskier
        if ($data['grand_total'] > 200000000) {
            $riskFactor += 0.3;
        } elseif ($data['grand_total'] > 100000000) {
            $riskFactor += 0.15;
        }

        // Risk factor: dari historical data, ada overrun patterns?
        $historicalOverrunRate = $this->getHistoricalOverrunRate($data);
        $riskFactor += $historicalOverrunRate;

        return min($riskFactor, 1.5); // Cap at 1.5
    }

    /**
     * Calculate complexity score (0-10)
     */
    private function calculateComplexityScore($data)
    {
        $complexity = 1; // Base score

        // More items = more complex
        if ($data['items_count'] > 20) {
            $complexity += 5;
        } elseif ($data['items_count'] > 10) {
            $complexity += 3;
        } elseif ($data['items_count'] > 5) {
            $complexity += 2;
        }

        // Historical overrun data affects complexity
        if (!empty($data['historical_data'])) {
            $avgVariance = collect($data['historical_data'])
                ->avg('variance_percent');
            
            if ($avgVariance > 15) {
                $complexity += 3;
            } elseif ($avgVariance > 5) {
                $complexity += 1;
            }
        }

        return min($complexity, 10); // Cap at 10
    }

    /**
     * Hitung overrun rate dari historical data
     */
    private function getHistoricalOverrunRate($data)
    {
        $allHistorical = DB::table('penawaran as p')
            ->join('proyek as pr', 'p.id', '=', 'pr.penawaran_id')
            ->leftJoin('pengeluaran as pg', 'pr.id', '=', 'pg.proyek_id')
            ->where('pr.status', 'selesai')
            ->select(
                'p.id',
                'pr.id as proyek_id',
                'p.grand_total_with_ppn as quoted_cost',
                DB::raw('COALESCE(SUM(pg.jumlah), 0) as actual_cost')
            )
            ->groupBy(['p.id', 'pr.id', 'p.grand_total_with_ppn'])
            ->get();

        if ($allHistorical->isEmpty()) {
            return 0.2; // Default risk 20%
        }

        $overrunCount = $allHistorical->filter(function ($item) {
            return $item->actual_cost > $item->quoted_cost;
        })->count();

        return $overrunCount / count($allHistorical);
    }

    /**
     * Auto-create materials dari BOQ items yang tidak punya material_id
     * Dijalankan ketika penawaran disetujui atau di-revisi
     * 
     * @return array Info tentang material yang dibuat
     */
    private function createMaterialsFromBoQItems(Penawaran $penawaran)
    {
        $result = [
            'created' => 0,
            'skipped' => 0,
            'errors' => [],
            'materials' => []
        ];

        try {
            \Log::info("Starting createMaterialsFromBoQItems for penawaran {$penawaran->id}");
            
            // Get semua items yang tidak punya material_id
            $itemsWithoutMaterial = ItemPenawaran::where('penawaran_id', $penawaran->id)
                ->whereNull('material_id')
                ->get();

            \Log::info("Found {$itemsWithoutMaterial->count()} items without material_id");

            foreach ($itemsWithoutMaterial as $item) {
                try {
                    \Log::info("Processing item {$item->id}: nama='{$item->nama}', satuan='{$item->satuan}'");
                    
                    // CHECK: Apakah material dengan kode ini sudah ada (prevent duplicates)
                    $materialKode = 'BOQ-' . $penawaran->no_penawaran . '-' . $item->id;
                    $existingMaterial = Material::where('kode', $materialKode)->first();
                    
                    if ($existingMaterial) {
                        \Log::info("Material with kode {$materialKode} already exists (ID: {$existingMaterial->id})");
                        
                        // Update item dengan existing material_id
                        $item->update([
                            'material_id' => $existingMaterial->id,
                        ]);
                        
                        $result['skipped']++;
                        continue;
                    }
                    
                    // Create material baru (hanya jika belum ada)
                    $material = Material::create([
                        'kode' => $materialKode,
                        'nama' => $item->nama ?? 'Material BoQ Item',
                        'satuan' => $item->satuan ?? 'pcs',
                        'supplier_id' => null, // Dari BOQ, belum ada supplier
                        'harga' => $item->harga_asli,  // Use 'harga' not 'harga_beli'
                        'type' => 'BARANG',  // Default type untuk BoQ items
                    ]);

                    \Log::info("Created material {$material->id} with kode {$material->kode}");

                    // Update item dengan material_id yang baru
                    $item->update([
                        'material_id' => $material->id,
                    ]);

                    \Log::info("Updated item {$item->id} with material_id {$material->id}");

                    $result['created']++;
                    $result['materials'][] = [
                        'id' => $material->id,
                        'kode' => $material->kode,
                        'nama' => $material->nama,
                        'satuan' => $material->satuan,
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing item {$item->id}: " . $e->getMessage());
                    $result['errors'][] = "Item {$item->id} ({$item->nama}): " . $e->getMessage();
                    $result['skipped']++;
                }
            }

            \Log::info("Completed createMaterialsFromBoQItems: {$result['created']} created, {$result['skipped']} skipped");
        } catch (\Exception $e) {
            \Log::error("Error in createMaterialsFromBoQItems: " . $e->getMessage());
            $result['errors'][] = "Kesalahan saat membuat materials: " . $e->getMessage();
        }

        return $result;
    }
}
