<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\ItemPenawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ]);

            // Update penawaran dengan hasil prediksi
            $penawaran->update([
                'ai_status' => 'analyzed',
                'ai_prediksi_lr' => $prediction['prediksi_lr'],
                'ai_prediksi_ma' => $prediction['prediksi_ma'],
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
                    'ai_prediksi_lr' => (float) $prediction['prediksi_lr'],
                    'ai_prediksi_ma' => (float) $prediction['prediksi_ma'],
                    'margin_status' => $prediction['margin_status'],
                    'risk_level' => $prediction['risk_level'],
                    'estimated_actual_cost' => (float) $prediction['estimated_actual_cost'],
                    'estimated_overrun_percent' => (float) $prediction['estimated_overrun_percent'],
                    'ai_notes' => $prediction['ai_notes'],
                    'recommendation' => $prediction['recommendation'],
                    'predictions' => [
                        'linear_regression' => (float) $prediction['prediksi_lr'],
                        'moving_average' => (float) $prediction['prediksi_ma'],
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
     */
    public function approvePenawaran(Request $request)
    {
        try {
            $validated = $request->validate([
                'penawaran_id' => 'required|exists:penawaran,id',
                'user_decision' => 'required|in:approve,reject,revise',
                'notes' => 'nullable|string',
            ]);

            $penawaran = Penawaran::find($validated['penawaran_id']);

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

            // User decision (manajer/direktur)
            if ($validated['user_decision'] === 'approve') {
                $penawaran->update([
                    'status' => 'disetujui',
                    'ai_status' => 'approved',
                ]);

                // Auto-create materials for BOQ items without material_id
                $materialResult = $this->createMaterialsFromBoQItems($penawaran);

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
                // 'revise' - juga perlu create materials biar user bisa edit dengan material dropdown
                $penawaran->update([
                    'ai_status' => 'pending', // Reset untuk dianalisis ulang
                ]);

                // Auto-create materials so user can revise with proper material selections
                $materialResult = $this->createMaterialsFromBoQItems($penawaran);

                $message = 'Penawaran dikembalikan untuk revisi.';
                if ($materialResult['created'] > 0) {
                    $message .= ' ' . $materialResult['created'] . ' material siap untuk digunakan.';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'penawaran_id' => $penawaran->id,
                    'status' => $penawaran->status,
                    'ai_status' => $penawaran->ai_status,
                    'materials_created' => ($validated['user_decision'] === 'approve' || $validated['user_decision'] === 'revise') ? $materialResult['created'] : 0,
                    'materials_info' => ($validated['user_decision'] === 'approve' || $validated['user_decision'] === 'revise') ? $materialResult : null,
                ]
            ]);

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
        // TODO: Integrate dengan Python API
        // Untuk sekarang, return dummy prediction (hardcoded)
        
        $quotedCost = $data['grand_total'];
        
        // Dummy prediction logic: berikan warning untuk proyek besar
        // atau yang punya karakteristik tertentu
        $riskFactor = $this->calculateRiskFactor($data);
        
        // Linear Regression prediction (mimic: +5% untuk high risk)
        $prediksiLR = $quotedCost * (1 + ($riskFactor * 0.05));
        
        // Moving Average prediction (mimic: +3% lebih konservatif)
        $prediksiMA = $quotedCost * (1 + ($riskFactor * 0.03));
        
        $estimatedActualCost = ($prediksiLR + $prediksiMA) / 2;
        $overrunPercent = (($estimatedActualCost - $quotedCost) / $quotedCost) * 100;
        
        // Get historical overrun rate untuk adjust risk assessment
        $historicalOverrunRate = $this->getHistoricalOverrunRate($data) * 100;
        
        // === RISK ASSESSMENT LOGIC ===
        // Historical data adalah PRIMARY factor karena terbukti dari data nyata
        
        $marginStatus = 'aman';
        $riskLevel = 'low';
        $recommendation = 'Penawaran dapat dilanjutkan.';
        
        // STEP 1: Base risk dari historical data (PRIORITAS UTAMA)
        if ($historicalOverrunRate >= 30) {
            // 30%+ dari past projects overrun = HIGH RISK
            $riskLevel = 'high';
            $marginStatus = 'overrun';
            $recommendation = '🔴 DANGER: Data historis menunjukkan ' . round($historicalOverrunRate, 1) . '% projects mengalami overrun/loss. Risiko sangat tinggi!';
        } elseif ($historicalOverrunRate >= 20) {
            // 20-30% dari past projects overrun = MEDIUM RISK  
            $riskLevel = 'medium';
            $marginStatus = 'overrun';
            $recommendation = '⚠️ WARNING: Data historis menunjukkan ' . round($historicalOverrunRate, 1) . '% projects mengalami loss. Pertimbangkan untuk revisi estimasi atau penolakan.';
        } elseif ($historicalOverrunRate >= 15) {
            // 15-20% dari past projects overrun = MEDIUM RISK
            $riskLevel = 'medium';
            $recommendation = '⚠️ Data historis menunjukkan ' . round($historicalOverrunRate, 1) . '% overrun rate. Review estimasi biaya dan scope dengan hati-hati.';
        }
        
        // STEP 2: Upgrade risk jika prediction variance juga tinggi
        if ($overrunPercent > 15) {
            // Prediction juga pessimistic = VERY HIGH RISK
            if ($riskLevel === 'low') {
                $riskLevel = 'high';
            }
            $marginStatus = 'overrun';
            $recommendation = '🔴 DANGER: Prediction overrun ' . round($overrunPercent, 1) . '% + historical overrun ' . round($historicalOverrunRate, 1) . '%. TIDAK DIREKOMENDASIKAN.';
        } elseif ($overrunPercent > 10) {
            // Prediction moderate pessimistic
            if ($riskLevel === 'low') {
                $riskLevel = 'medium';
            }
            $marginStatus = 'overrun';
            if (strpos($recommendation, '🔴') === false && strpos($recommendation, '⚠️') === false) {
                $recommendation = '⚠️ Prediction overrun ' . round($overrunPercent, 1) . '%. ' . $recommendation;
            }
        } elseif ($overrunPercent > 5 && $riskLevel === 'low') {
            // Prediction slightly pessimistic
            $riskLevel = 'medium';
            $recommendation = 'Prediction overrun ' . round($overrunPercent, 1) . '%. Review margin dan scope.';
        }
        
        // STEP 3: Final check - if still low risk but has any positive overrun, upgrade to medium
        if ($riskLevel === 'low' && $overrunPercent > 0 && ($historicalOverrunRate > 0 || $overrunPercent > 1)) {
            $riskLevel = 'medium';
            $marginStatus = 'overrun';
            $recommendation = 'Ada indikasi potential overrun. Rekomendasi untuk review kembali estimasi.';
        }

        $notes = "Analisis DSS: {$data['items_count']} item, historical overrun " . round($historicalOverrunRate, 1) . "%, ";
        $notes .= "prediksi actual cost Rp " . number_format($estimatedActualCost, 0) . " dengan variance " . round($overrunPercent, 1) . "%.";

        return [
            'prediksi_lr' => $prediksiLR,
            'prediksi_ma' => $prediksiMA,
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
     * Dijalankan ketika penawaran disetujui
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
            // Get semua items yang tidak punya material_id
            $itemsWithoutMaterial = ItemPenawaran::where('penawaran_id', $penawaran->id)
                ->whereNull('material_id')
                ->get();

            foreach ($itemsWithoutMaterial as $item) {
                try {
                    // Create material baru
                    $material = Material::create([
                        'kode' => 'BOQ-' . $penawaran->no_penawaran . '-' . $item->id,
                        'nama' => $item->nama ?? 'Material BoQ Item',
                        'satuan' => $item->satuan ?? 'pcs',
                        'supplier_id' => null, // Dari BOQ, belum ada supplier
                        'harga_beli' => $item->harga_asli,
                    ]);

                    // Create inventory entry untuk material ini
                    Inventory::create([
                        'material_id' => $material->id,
                        'stok' => 0,
                        'min_stok' => 0,
                    ]);

                    // Update item dengan material_id yang baru
                    $item->update([
                        'material_id' => $material->id,
                    ]);

                    $result['created']++;
                    $result['materials'][] = [
                        'id' => $material->id,
                        'kode' => $material->kode,
                        'nama' => $material->nama,
                        'satuan' => $material->satuan,
                    ];
                } catch (\Exception $e) {
                    $result['errors'][] = "Item {$item->id} ({$item->nama}): " . $e->getMessage();
                    $result['skipped']++;
                }
            }

            \Log::info("Materials created for penawaran {$penawaran->id}: " . $result['created'] . " created, " . $result['skipped'] . " skipped");
        } catch (\Exception $e) {
            \Log::error("Error in createMaterialsFromBoQItems: " . $e->getMessage());
            $result['errors'][] = "Kesalahan saat membuat materials: " . $e->getMessage();
        }

        return $result;
    }
}
