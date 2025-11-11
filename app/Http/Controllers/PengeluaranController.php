<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Proyek;
use App\Models\ProyekBudget;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of all pengeluaran
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::with('proyek', 'creator')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new pengeluaran
     */
    public function create()
    {
        $projects = Proyek::all();
        return view('pengeluaran.create', compact('projects'));
    }

    /**
     * Store a newly created pengeluaran in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyek_id' => 'required|exists:proyek,id',
            'tanggal' => 'required|date',
            'kategori' => 'required|in:material,gaji,bahan_bakar,peralatan,lainnya',
            'deskripsi' => 'required|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
        ], [
            'proyek_id.required' => 'Proyek harus dipilih',
            'proyek_id.exists' => 'Proyek yang dipilih tidak ditemukan',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Kategori tidak valid',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
        ]);

        $validated['dibuat_oleh'] = auth()->id();

        $pengeluaran = Pengeluaran::create($validated);

        // Update ProyekBudget - hitung total pengeluaran untuk proyek ini
        $this->updateBudgetRealisasi($validated['proyek_id']);

        // Get the ProyekBudget to redirect to budget detail page
        $budget = ProyekBudget::where('proyek_id', $validated['proyek_id'])->first();

        return redirect()->route('finance.budget.show', $budget->id)
            ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Show the form for editing a pengeluaran
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        $projects = Proyek::all();
        return view('pengeluaran.edit', compact('pengeluaran', 'projects'));
    }

    /**
     * Update a pengeluaran in database
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'proyek_id' => 'required|exists:proyek,id',
            'tanggal' => 'required|date',
            'kategori' => 'required|in:material,gaji,bahan_bakar,peralatan,lainnya',
            'deskripsi' => 'required|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
        ], [
            'proyek_id.required' => 'Proyek harus dipilih',
            'proyek_id.exists' => 'Proyek yang dipilih tidak ditemukan',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Kategori tidak valid',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
        ]);

        $oldProyekId = $pengeluaran->proyek_id;
        $pengeluaran->update($validated);

        // Update budget untuk proyek lama dan baru (jika berbeda)
        $this->updateBudgetRealisasi($oldProyekId);
        if ($oldProyekId !== $validated['proyek_id']) {
            $this->updateBudgetRealisasi($validated['proyek_id']);
        }

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Delete a pengeluaran from database
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        $proyekId = $pengeluaran->proyek_id;
        $pengeluaran->delete();

        // Update ProyekBudget setelah delete
        $this->updateBudgetRealisasi($proyekId);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus');
    }

    /**
     * Update ProyekBudget realisasi berdasarkan total pengeluaran
     * Public method untuk digunakan di seeder dan testing
     */
    public function updateBudgetRealisasi($proyekId)
    {
        $budget = ProyekBudget::where('proyek_id', $proyekId)->first();
        
        if ($budget) {
            // Hitung total pengeluaran untuk proyek ini
            $totalPengeluaran = Pengeluaran::where('proyek_id', $proyekId)->sum('jumlah');
            
            // Update jumlah_realisasi
            $budget->update([
                'jumlah_realisasi' => $totalPengeluaran
            ]);
        }
    }
}
