<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::with('supplier')->get();
        $materialsNoStock = $materials->whereNotIn('id', DB::table('inventories')->pluck('material_id'))->count();
        return view('materials.index', compact('materials', 'materialsNoStock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('materials.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0'
        ]);

        Material::create($validated);
        return redirect()->route('material.index')->with('success', 'Material berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $suppliers = Supplier::all();
        return view('materials.edit', compact('material', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0'
        ]);

        $material->update($validated);
        return redirect()->route('material.index')->with('success', 'Material berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('material.index')->with('success', 'Material berhasil dihapus');
    }
}
