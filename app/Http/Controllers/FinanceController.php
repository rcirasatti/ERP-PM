<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProyekBudget;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display finance dashboard
     */
    public function dashboard()
    {
        // Total Budget dari semua proyek
        $total_budget = ProyekBudget::sum('jumlah_rencana');
        
        // Total Realisasi/Pengeluaran
        $total_realisasi = ProyekBudget::sum('jumlah_realisasi');
        
        // Sisa Budget
        $sisa_budget = $total_budget - $total_realisasi;
        
        // Persentase Penggunaan
        $persentase_penggunaan = $total_budget > 0 ? ($total_realisasi / $total_budget) * 100 : 0;
        
        // Budget per Proyek
        $budget_per_proyek = ProyekBudget::with(['proyek.client'])
            ->get()
            ->map(function ($budget) {
                return [
                    'proyek_nama' => $budget->proyek->nama,
                    'client_nama' => $budget->proyek->client->nama,
                    'jumlah_rencana' => $budget->jumlah_rencana,
                    'jumlah_realisasi' => $budget->jumlah_realisasi,
                    'sisa' => $budget->sisa_budget,
                    'persentase' => $budget->persentase_penggunaan,
                    'status' => $budget->getStatusBudget(),
                    'status_color' => $budget->getStatusColor(),
                ];
            });
        
        // Pengeluaran per Kategori
        $pengeluaran_per_kategori = Pengeluaran::select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get()
            ->map(function ($item) {
                return [
                    'kategori' => $item->kategori,
                    'total' => $item->total,
                    'label' => match($item->kategori) {
                        'material' => 'Material',
                        'gaji' => 'Gaji',
                        'bahan_bakar' => 'Bahan Bakar',
                        'peralatan' => 'Peralatan',
                        'lainnya' => 'Lainnya',
                        default => 'Unknown'
                    }
                ];
            });
        
        // Top 5 Proyek dengan Budget Terbesar
        $top_proyek = ProyekBudget::with(['proyek.client'])
            ->orderBy('jumlah_rencana', 'desc')
            ->take(5)
            ->get();
        
        // Proyek dengan Budget Hampir Habis (>80%)
        $proyek_kritis = ProyekBudget::with(['proyek.client'])
            ->get()
            ->filter(function ($budget) {
                return $budget->persentase_penggunaan > 80;
            })
            ->sortByDesc('persentase_penggunaan')
            ->take(5);
        
        // Pengeluaran Terbaru
        $pengeluaran_terbaru = Pengeluaran::with(['proyek', 'creator'])
            ->orderBy('tanggal', 'desc')
            ->take(10)
            ->get();
        
        // Statistik Bulanan (6 bulan terakhir)
        $bulan_labels = [];
        $bulan_budget = [];
        $bulan_realisasi = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $bulan_labels[] = $month->format('M Y');
            
            // Budget yang dibuat bulan ini (dari proyek yang dibuat)
            $budget_bulan = ProyekBudget::whereHas('proyek', function ($q) use ($month) {
                $q->whereYear('created_at', $month->year)
                  ->whereMonth('created_at', $month->month);
            })->sum('jumlah_rencana');
            
            $bulan_budget[] = $budget_bulan;
            
            // Pengeluaran bulan ini
            $realisasi_bulan = Pengeluaran::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah');
            
            $bulan_realisasi[] = $realisasi_bulan;
        }
        
        // Alias untuk compatibility dengan view
        $recent_pengeluaran = $pengeluaran_terbaru;
        
        // Prepare kategori labels dan totals untuk chart
        $kategori_labels = $pengeluaran_per_kategori->pluck('label')->toArray();
        $kategori_totals = $pengeluaran_per_kategori->pluck('total')->toArray();

        return view('finance.dashboard', compact(
            'total_budget',
            'total_realisasi',
            'sisa_budget',
            'persentase_penggunaan',
            'budget_per_proyek',
            'pengeluaran_per_kategori',
            'top_proyek',
            'proyek_kritis',
            'pengeluaran_terbaru',
            'recent_pengeluaran',
            'bulan_labels',
            'bulan_budget',
            'bulan_realisasi',
            'kategori_labels',
            'kategori_totals'
        ));
    }

    /**
     * Display list of all budgets
     */
    public function budget(Request $request)
    {
        $query = ProyekBudget::with(['proyek.client']);
        
        // Search by proyek name or client name
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->whereHas('proyek', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhereHas('client', function ($q2) use ($search) {
                      $q2->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $budgets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('finance.budget', compact('budgets'));
    }

    /**
     * Display budget detail page
     */
    public function showBudget(ProyekBudget $budget)
    {
        $budget->load(['proyek.client', 'proyek.pengeluaran']);
        
        // Get all pengeluaran for this proyek
        $pengeluarans = Pengeluaran::where('proyek_id', $budget->proyek_id)
            ->with(['creator', 'proyek'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('finance.budget.show', compact('budget', 'pengeluarans'));
    }

    /**
     * Display list of all expenses
     */
    public function pengeluaran()
    {
        $pengeluarans = Pengeluaran::with(['proyek', 'creator'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        
        $proyeks = Proyek::orderBy('nama')->get();
        
        return view('finance.pengeluaran', compact('pengeluarans', 'proyeks'));
    }

    /**
     * Store a new expense
     */
    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'proyek_id' => 'required|exists:proyek,id',
            'tanggal' => 'required|date',
            'kategori' => 'required|in:material,gaji,bahan_bakar,peralatan,lainnya',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $validated['dibuat_oleh'] = auth()->id();

        $pengeluaran = Pengeluaran::create($validated);

        // Update budget realisasi
        $budget = ProyekBudget::where('proyek_id', $validated['proyek_id'])->first();
        if ($budget) {
            $total_pengeluaran = Pengeluaran::where('proyek_id', $validated['proyek_id'])->sum('jumlah');
            $budget->update(['jumlah_realisasi' => $total_pengeluaran]);
        }

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Update an expense
     */
    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:material,gaji,bahan_bakar,peralatan,lainnya',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $proyek_id = $pengeluaran->proyek_id;
        $pengeluaran->update($validated);

        // Update budget realisasi
        $budget = ProyekBudget::where('proyek_id', $proyek_id)->first();
        if ($budget) {
            $total_pengeluaran = Pengeluaran::where('proyek_id', $proyek_id)->sum('jumlah');
            $budget->update(['jumlah_realisasi' => $total_pengeluaran]);
        }

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Delete an expense
     */
    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $proyek_id = $pengeluaran->proyek_id;
        $pengeluaran->delete();

        // Update budget realisasi
        $budget = ProyekBudget::where('proyek_id', $proyek_id)->first();
        if ($budget) {
            $total_pengeluaran = Pengeluaran::where('proyek_id', $proyek_id)->sum('jumlah');
            $budget->update(['jumlah_realisasi' => $total_pengeluaran]);
        }

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus');
    }
}
