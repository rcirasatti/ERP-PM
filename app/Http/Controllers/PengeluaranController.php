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
    public function create(Request $request)
    {
        $projects = Proyek::all();
        $proyek_id = $request->query('proyek_id');
        $selectedProyek = null;
        
        // Jika dari budget, auto-detect proyek
        if ($proyek_id) {
            $selectedProyek = Proyek::find($proyek_id);
        }
        
        return view('pengeluaran.create', compact('projects', 'proyek_id', 'selectedProyek'));
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
            'bukti_file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx|max:5120',
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
            'bukti_file.required' => 'Bukti file harus diunggah',
            'bukti_file.file' => 'File harus berupa file yang valid',
            'bukti_file.mimes' => 'File harus bertipe: PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX',
            'bukti_file.max' => 'Ukuran file tidak boleh lebih dari 5 MB',
        ]);

        $validated['dibuat_oleh'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengeluaran', $fileName, 'public');
            $validated['bukti_file'] = 'pengeluaran/' . $fileName;
        }

        $pengeluaran = Pengeluaran::create($validated);

        // Update ProyekBudget - hitung total pengeluaran untuk proyek ini
        $this->updateBudgetRealisasi($validated['proyek_id']);

        // Check where request came from
        $from = $request->input('from', 'index'); // default to index
        
        if ($from === 'budget' && $request->input('budget_id')) {
            // Redirect to budget show page
            return redirect()->route('finance.budget.show', $request->input('budget_id'))
                ->with('success', 'Pengeluaran berhasil ditambahkan');
        } else {
            // Redirect to pengeluaran index
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Pengeluaran berhasil ditambahkan');
        }
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
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx|max:5120',
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
            'bukti_file.file' => 'File harus berupa file yang valid',
            'bukti_file.mimes' => 'File harus bertipe: PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX',
            'bukti_file.max' => 'Ukuran file tidak boleh lebih dari 5 MB',
        ]);

        // Handle file upload
        if ($request->hasFile('bukti_file')) {
            // Delete old file if exists
            if ($pengeluaran->bukti_file && \Storage::disk('public')->exists($pengeluaran->bukti_file)) {
                \Storage::disk('public')->delete($pengeluaran->bukti_file);
            }
            
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengeluaran', $fileName, 'public');
            $validated['bukti_file'] = 'pengeluaran/' . $fileName;
        }

        $oldProyekId = $pengeluaran->proyek_id;
        $pengeluaran->update($validated);

        // Update budget untuk proyek lama dan baru (jika berbeda)
        $this->updateBudgetRealisasi($oldProyekId);
        if ($oldProyekId !== $validated['proyek_id']) {
            $this->updateBudgetRealisasi($validated['proyek_id']);
        }

        // Check where request came from
        $from = $request->input('from', 'index'); // default to index
        
        if ($from === 'budget' && $request->input('budget_id')) {
            // Redirect to budget show page
            return redirect()->route('finance.budget.show', $request->input('budget_id'))
                ->with('success', 'Pengeluaran berhasil diperbarui');
        } else {
            // Redirect to pengeluaran index
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Pengeluaran berhasil diperbarui');
        }
    }

    /**
     * Delete a pengeluaran from database
     */
    public function destroy(Pengeluaran $pengeluaran, Request $request)
    {
        $proyekId = $pengeluaran->proyek_id;
        
        // Delete file if exists
        if ($pengeluaran->bukti_file && \Storage::disk('public')->exists($pengeluaran->bukti_file)) {
            \Storage::disk('public')->delete($pengeluaran->bukti_file);
        }
        
        $pengeluaran->delete();

        // Update ProyekBudget setelah delete
        $this->updateBudgetRealisasi($proyekId);

        // Check where request came from
        $from = $request->input('from', 'index'); // default to index
        
        if ($from === 'budget' && $request->input('budget_id')) {
            // Redirect to budget show page
            return redirect()->route('finance.budget.show', $request->input('budget_id'))
                ->with('success', 'Pengeluaran berhasil dihapus');
        } else {
            // Redirect to pengeluaran index
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Pengeluaran berhasil dihapus');
        }
    }

    /**
     * Download bukti file with authorization
     */
    public function downloadBukti(Pengeluaran $pengeluaran)
    {
        if (!auth()->check()) {
            abort(401, 'Silakan login terlebih dahulu');
        }

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini');
        }

        $filePath = storage_path('app/public/' . $pengeluaran->bukti_file);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, basename($pengeluaran->bukti_file));
    }

    /**
     * Preview bukti file in browser with authorization
     */
    public function previewBukti(Pengeluaran $pengeluaran)
    {
        if (!auth()->check()) {
            abort(401, 'Silakan login terlebih dahulu');
        }

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini');
        }

        $filePath = storage_path('app/public/' . $pengeluaran->bukti_file);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath);
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
