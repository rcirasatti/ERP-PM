<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Material;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with('material')->get();
        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        return view('inventories.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'stok' => 'required|numeric|min:0'
        ]);

        // Check if inventory already exists for this material
        $existingInventory = Inventory::where('material_id', $validated['material_id'])->first();

        if ($existingInventory) {
            // Add to existing inventory (increment stok)
            $newStok = $existingInventory->stok + $validated['stok'];
            $existingInventory->update(['stok' => $newStok]);
            return redirect()->route('inventory.index')->with('info', "Stok ditambahkan. Total stok sekarang: {$newStok}");
        } else {
            // Create new inventory
            Inventory::create($validated);
            return redirect()->route('inventory.index')->with('success', 'Inventory berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $materials = Material::all();
        return view('inventories.edit', compact('inventory', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id|unique:inventories,material_id,' . $inventory->id,
            'stok' => 'required|numeric|min:0'
        ]);

        $inventory->update($validated);
        return redirect()->route('inventory.index')->with('success', 'Inventory berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory berhasil dihapus');
    }
}
