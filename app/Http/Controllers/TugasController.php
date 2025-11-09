<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Display all tasks for a project
     */
    public function index(Proyek $proyek)
    {
        $tugas_list = $proyek->tugas()->with('user')->get();

        return view('tugas.index', compact('proyek', 'tugas_list'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create(Proyek $proyek)
    {
        $users = User::all();

        return view('tugas.create', compact('proyek', 'users'));
    }

    /**
     * Store a newly created task in storage
     */
    public function store(Request $request, Proyek $proyek)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $tugas = Tugas::create([
            'proyek_id' => $proyek->id,
            'nama' => $validated['nama'],
            'selesai' => false,
        ]);

        // Recalculate project progress
        $proyek->hitungProgress();
        $proyek->refresh();

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil ditambahkan',
                'task' => $tugas,
                'progress' => $proyek->persentase_progres,
            ]);
        }

        return redirect()->route('proyek.show', $proyek->id)
            ->with('success', 'Task berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Proyek $proyek, Tugas $tugas)
    {
        // Route model binding has already ensured that task belongs to project
        $users = User::all();

        return view('tugas.edit', compact('proyek', 'tugas', 'users'));
    }

    /**
     * Update the specified task in storage
     */
    public function update(Request $request, Proyek $proyek, Tugas $tugas)
    {
        // Route model binding has already ensured that task belongs to project
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'selesai' => 'boolean',
        ]);

        $tugas->update($validated);

        // Recalculate project progress
        $proyek->hitungProgress();

        return redirect()->route('proyek.show', $proyek->id)
            ->with('success', 'Task berhasil diperbarui');
    }

    /**
     * Remove the specified task from storage
     */
    public function destroy(Proyek $proyek, Tugas $tugas)
    {
        // Delete the task
        $tugas->delete();

        // Recalculate project progress
        $proyek->hitungProgress();
        $proyek->refresh();

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dihapus',
                'progress' => $proyek->persentase_progres,
            ]);
        }

        return redirect()->route('proyek.show', $proyek->id)
            ->with('success', 'Task berhasil dihapus');
    }

    /**
     * Update task status via AJAX
     */
    public function updateStatus(Request $request, Proyek $proyek, Tugas $tugas)
    {
        // Route model binding has already ensured that $tugas belongs to $proyek
        
        $validated = $request->validate([
            'selesai' => 'required|boolean',
        ]);

        $tugas->update(['selesai' => $validated['selesai']]);

        // Recalculate project progress
        $proyek->hitungProgress();
        $proyek->refresh(); // Refresh to get updated progress

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui',
            'progress' => $proyek->persentase_progres,
        ]);
    }
}
