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
        $budget->load(['proyek.client']);
        
        // Get all pengeluaran for this proyek - sorted by tanggal DESC (newest first)
        $pengeluarans = Pengeluaran::where('proyek_id', $budget->proyek_id)
            ->with(['creator', 'proyek'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
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
