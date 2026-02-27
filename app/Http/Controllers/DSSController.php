<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\Pengeluaran;
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

            return response()->json([
                'success' => true,
                'message' => 'Analisis DSS selesai',
                'data' => [
                    'penawaran_id' => $penawaran->id,
                    'no_penawaran' => $penawaran->no_penawaran,
                    'grand_total_quoted' => $validated['grand_total'],
                    'ai_prediksi_lr' => (float) $prediction['prediksi_lr'],
                    'ai_prediksi_ma' => (float) $prediction['prediksi_ma'],
                    'margin_status' => $prediction['margin_status'],
                    'risk_level' => $prediction['risk_level'],
                    'estimated_actual_cost' => (float) $prediction['estimated_actual_cost'],
                    'estimated_overrun_percent' => $prediction['estimated_overrun_percent'],
                    'ai_notes' => $prediction['ai_notes'],
                    'recommendation' => $prediction['recommendation'],
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

        // User decision (manajer/direktur)
        if ($validated['user_decision'] === 'approve') {
            $penawaran->update([
                'status' => 'disetujui',
                'ai_status' => 'approved',
            ]);

            $message = 'Penawaran disetujui dan berlanjut ke tahap proyek.';
        } elseif ($validated['user_decision'] === 'reject') {
            $penawaran->update([
                'status' => 'ditolak',
                'ai_status' => 'approved',
            ]);

            $message = 'Penawaran ditolak berdasarkan rekomendasi DSS.';
        } else {
            $penawaran->update([
                'ai_status' => 'pending', // Reset untuk dianalisis ulang
            ]);

            $message = 'Penawaran dikembalikan untuk revisi.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'penawaran_id' => $penawaran->id,
                'status' => $penawaran->status,
                'ai_status' => $penawaran->ai_status,
            ]
        ]);
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
            ->groupBy('p.id', 'pr.id', 'p.grand_total_with_ppn')
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
        
        // Determine status & risk level
        $marginStatus = 'aman';
        $riskLevel = 'low';
        $recommendation = 'Penawaran dapat dilanjutkan.';
        
        if ($overrunPercent > 5) {
            $marginStatus = 'overrun';
            $riskLevel = 'medium';
            $recommendation = 'Warning: Prediksi overrun ' . round($overrunPercent, 1) . '%. Review margin dan scope.';
        }
        
        if ($overrunPercent > 10) {
            $riskLevel = 'high';
            $recommendation = 'DANGER: Prediksi overrun ' . round($overrunPercent, 1) . '%. Tidak direkomendasikan atau revisi drastis diperlukan.';
        }

        $notes = "Analisis DSS (Dummy Model): Berdasarkan {$data['items_count']} item dan historical data, ";
        $notes .= "sistem memprediksi actual cost mencapai Rp " . number_format($estimatedActualCost, 0) . ".";

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
     * Hitung overrun rate dari historical data
     */
    private function getHistoricalOverrunRate($data)
    {
        $allHistorical = DB::table('penawaran as p')
            ->join('proyek as pr', 'p.id', '=', 'pr.penawaran_id')
            ->leftJoin('pengeluaran as pg', 'pr.id', '=', 'pg.proyek_id')
            ->where('pr.status', 'selesai')
            ->select(
                DB::raw('COALESCE(SUM(pg.jumlah), 0) as actual_cost'),
                'p.grand_total_with_ppn as quoted_cost'
            )
            ->groupBy('p.id')
            ->get();

        if ($allHistorical->isEmpty()) {
            return 0.2; // Default risk 20%
        }

        $overrunCount = $allHistorical->filter(function ($item) {
            return $item->actual_cost > $item->quoted_cost;
        })->count();

        return $overrunCount / count($allHistorical);
    }
}
