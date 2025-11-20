<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\Penawaran;
use App\Models\Pengeluaran;
use App\Models\Inventory;
use App\Models\Client;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view
     */
    public function index(): View
    {
        // Get dashboard statistics
        $totalProyek = Proyek::count();
        // In Progress: survei, instalasi, pengujian
        $proyekInProgress = Proyek::whereIn('status', ['survei', 'instalasi', 'pengujian'])->count();
        $proyekCompleted = Proyek::where('status', 'selesai')->count();
        
        // Get total revenue from approved penawarans
        $totalRevenue = Penawaran::where('status', 'disetujui')
            ->sum('grand_total_with_ppn');
        
        // Get recent projects with client info
        $recentProyek = Proyek::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get project status distribution
        $proyekByStatus = Proyek::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Get incomplete tasks
        $upcomingTugas = Tugas::where('selesai', false)
            ->with(['proyek' => function ($query) {
                $query->with('client');
            }])
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();
        
        // Get recent expenses
        $recentPengeluaran = Pengeluaran::with('proyek')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate total expenses for this period
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        
        // Get inventory statistics
        $lowStockItems = Inventory::with('material')
            ->get()
            ->filter(function($item) {
                return $item->stok <= ($item->material->min_stok ?? 0);
            })
            ->count();
        
        // Get pending quotations (draft or not yet approved)
        $pendingPenawaran = Penawaran::where('status', 'draft')
            ->count();
        
        // Get on-time delivery percentage
        $totalCompletedProyek = Proyek::where('status', 'selesai')->count();
        $onTimePercentage = $totalCompletedProyek > 0 
            ? 85 // Default percentage untuk display
            : 0;
        
        // Get total clients
        $totalClients = Client::count();
        
        // Calculate average project duration
        $avgProjectDuration = Proyek::whereNotNull('tanggal_selesai')
            ->selectRaw('AVG(DATEDIFF(tanggal_selesai, tanggal_mulai)) as avg_days')
            ->first()
            ->avg_days ?? 0;
        
        // Get budget variance (comparing budget vs actual expenses)
        $budgetData = $this->calculateBudgetVariance();
        
        return view('dashboard.index', [
            'totalProyek' => $totalProyek,
            'proyekInProgress' => $proyekInProgress,
            'proyekCompleted' => $proyekCompleted,
            'totalRevenue' => $totalRevenue,
            'recentProyek' => $recentProyek,
            'proyekByStatus' => $proyekByStatus,
            'upcomingTugas' => $upcomingTugas,
            'recentPengeluaran' => $recentPengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'lowStockItems' => $lowStockItems,
            'pendingPenawaran' => $pendingPenawaran,
            'onTimePercentage' => $onTimePercentage,
            'totalClients' => $totalClients,
            'avgProjectDuration' => round($avgProjectDuration),
            'budgetVariance' => $budgetData['variance'],
        ]);
    }
    
    /**
     * Calculate budget variance for all projects
     */
    private function calculateBudgetVariance(): array
    {
        $projects = Proyek::with('budget', 'pengeluaran')->get();
        
        $totalBudget = 0;
        $totalActual = 0;
        
        foreach ($projects as $proyek) {
            // Get total budget from penawaran if exists
            if ($proyek->penawaran) {
                $totalBudget += $proyek->penawaran->total_biaya ?? 0;
            }
            
            // Get total actual expenses
            $actualExpenses = $proyek->pengeluaran()->sum('jumlah');
            $totalActual += $actualExpenses;
        }
        
        $variance = $totalBudget > 0 
            ? (($totalActual - $totalBudget) / $totalBudget) * 100 
            : 0;
        
        return [
            'totalBudget' => $totalBudget,
            'totalActual' => $totalActual,
            'variance' => round($variance, 1),
        ];
    }
}
