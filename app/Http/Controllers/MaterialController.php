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
        $materials = Material::with('supplier', 'inventory')->get();
        $materialsNoStock = $materials->whereNotIn('id', DB::table('inventories')->pluck('material_id'))->count();
        return view('materials.index', compact('materials', 'materialsNoStock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $types = Material::getTypes();
        return view('materials.create', compact('suppliers', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', Material::TYPE_BARANG);
        
        // Validasi dinamis berdasarkan tipe
        $rules = [
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'type' => 'required|in:' . implode(',', array_keys(Material::getTypes())),
            'track_inventory' => 'sometimes|boolean',
        ];
        
        // Supplier hanya wajib untuk tipe BARANG
        if ($type === Material::TYPE_BARANG) {
            $rules['supplier_id'] = 'required|exists:suppliers,id';
        } else {
            $rules['supplier_id'] = 'nullable|exists:suppliers,id';
        }
        
        $validated = $request->validate($rules);
        
        // Set track_inventory default berdasarkan tipe
        if (!isset($validated['track_inventory'])) {
            $validated['track_inventory'] = ($type === Material::TYPE_BARANG);
        }

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
     * Show the form for editing the resource.
     */
    public function edit(Material $material)
    {
        $suppliers = Supplier::all();
        $types = Material::getTypes();
        return view('materials.edit', compact('material', 'suppliers', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $type = $request->input('type', $material->type);
        
        // Validasi dinamis berdasarkan tipe
        $rules = [
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'type' => 'required|in:' . implode(',', array_keys(Material::getTypes())),
            'track_inventory' => 'sometimes|boolean',
        ];
        
        // Supplier hanya wajib untuk tipe BARANG
        if ($type === Material::TYPE_BARANG) {
            $rules['supplier_id'] = 'required|exists:suppliers,id';
        } else {
            $rules['supplier_id'] = 'nullable|exists:suppliers,id';
        }
        
        $validated = $request->validate($rules);
        
        // Set track_inventory default berdasarkan tipe
        if (!isset($validated['track_inventory'])) {
            $validated['track_inventory'] = ($type === Material::TYPE_BARANG);
        }

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
