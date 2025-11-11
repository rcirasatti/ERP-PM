<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Client;
use App\Models\ProyekBudget;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    /**
     * Display a listing of all projects
     */
    public function index()
    {
        $proyeks = Proyek::with(['client', 'penawaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Calculate statistics
        $total_projects = Proyek::count();
        $completed_projects = Proyek::where('status', 'selesai')->count();
        $ongoing_projects = Proyek::whereIn('status', ['baru', 'survei', 'instalasi', 'pengujian'])->count();
        $unprojected_quotations = Penawaran::where('status', 'disetujui')
            ->whereDoesntHave('proyek')
            ->count();

        return view('proyek.index', compact('proyeks', 'total_projects', 'completed_projects', 'ongoing_projects', 'unprojected_quotations'));
    }

    /**
     * Show the form for creating a new project from approved quotation
     */
    public function create()
    {
        // Get only approved quotations that don't have a project yet
        $penawaran_disetujui = Penawaran::where('status', 'disetujui')
            ->whereDoesntHave('proyek')
            ->with('client')
            ->get();

        return view('proyek.create', compact('penawaran_disetujui'));
    }

    /**
     * Store a newly created project in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penawaran_id' => 'required|exists:penawaran,id|unique:proyek,penawaran_id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        // Get penawaran data
        $penawaran = Penawaran::findOrFail($validated['penawaran_id']);

        // Create project with automatic 'baru' status and 0% progress
        $proyek = Proyek::create([
            'penawaran_id' => $validated['penawaran_id'],
            'client_id' => $penawaran->client_id,
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status' => 'baru', // Always start as 'baru'
            'persentase_progres' => 0, // Initial progress is 0%
        ]);

        // Create budget automatically from penawaran grand_total
        ProyekBudget::create([
            'proyek_id' => $proyek->id,
            'jumlah_rencana' => $penawaran->grand_total,
            'jumlah_realisasi' => 0,
        ]);

        return redirect()->route('proyek.show', $proyek->id)
            ->with('success', 'Project berhasil dibuat dari penawaran yang disetujui');
    }

    /**
     * Display the specified project with its tasks
     */
    public function show(Proyek $proyek)
    {
        $proyek->load(['client', 'penawaran', 'tugas.user']);
        
        return view('proyek.show', compact('proyek'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    /**
     * Update the specified project in storage
     */
    public function update(Request $request, Proyek $proyek)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:baru,survei,instalasi,pengujian,selesai,bast',
        ]);

        $proyek->update($validated);

        return redirect()->route('proyek.show', $proyek->id)
            ->with('success', 'Project berhasil diperbarui');
    }

    /**
     * Remove the specified project from storage
     */
    public function destroy(Proyek $proyek)
    {
        $proyek->delete();

        return redirect()->route('proyek.index')
            ->with('success', 'Project berhasil dihapus');
    }

    /**
     * Update project status
     */
    public function updateStatus(Request $request, Proyek $proyek)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,survei,instalasi,pengujian,selesai,bast',
        ]);

        $proyek->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'message' => 'Status project berhasil diperbarui']);
    }

    /**
     * Search projects by name or client
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $proyeks = Proyek::with(['client', 'penawaran'])
            ->where(function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                  ->orWhereHas('client', function ($clientQuery) use ($query) {
                      $clientQuery->where('nama', 'like', "%{$query}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'html' => view('proyek.partials.project-grid', compact('proyeks'))->render(),
            'pagination' => (string) $proyeks->links(),
        ]);
    }
}
