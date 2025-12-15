<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Inventory;
use App\Models\Supplier;
use App\Exports\MaterialTemplateExport;
use App\Imports\MaterialImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hitung KPI sebelum pagination
        $allMaterials = Material::with('supplier', 'inventory')->get();
        $materialsNoStock = $allMaterials->whereNotIn('id', DB::table('inventories')->pluck('material_id'))->count();
        $totalMaterials = $allMaterials->count();
        $trackInventory = $allMaterials->where('track_inventory', true)->count();
        $nonTrackInventory = $allMaterials->where('track_inventory', false)->count();
        
        // Data dengan pagination
        $materials = Material::with('supplier', 'inventory')->orderBy('created_at', 'desc')->paginate(15);
        
        return view('materials.index', compact('materials', 'materialsNoStock', 'totalMaterials', 'trackInventory', 'nonTrackInventory'));
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
            'kode' => 'nullable|string|max:50',
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
        return redirect()->route('material.index')->with('success', 'Item Material berhasil ditambahkan');
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
            'kode' => 'nullable|string|max:50',
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

    /**
     * Export template Excel for material import
     */
    public function exportTemplate()
    {
        $export = new MaterialTemplateExport();
        return $export->download('template_material.xlsx');
    }

    /**
     * Preview import Excel file to detect duplicates and price changes
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        $file = $request->file('file');
        
        try {
            $import = new MaterialImport(previewMode: true);
            $import->import($file);

            return response()->json([
                'duplicates' => $import->duplicates,
                'newItems' => $import->newItems,
                'errors' => $import->errors,
                'totalDuplicates' => count($import->duplicates),
                'totalNew' => count($import->newItems),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'duplicates' => [],
                'newItems' => [],
                'errors' => ['Error membaca file: ' . $e->getMessage()],
                'totalDuplicates' => 0,
                'totalNew' => 0,
            ], 422);
        }
    }

    /**
     * Import materials from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        $file = $request->file('file');
        
        try {
            $import = new MaterialImport(previewMode: false);
            $import->import($file);

            return response()->json([
                'success' => true,
                'stats' => [
                    'totalProcessed' => $import->success,
                    'newItems' => $import->newItemsAdded,
                    'stokAdded' => $import->stokAdded,
                    'pricesUpdated' => $import->pricesUpdated,
                ],
                'items' => $import->importedItems,
                'errors' => $import->errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'stats' => [
                    'totalProcessed' => 0,
                    'newItems' => 0,
                    'stokAdded' => 0,
                    'pricesUpdated' => 0,
                ],
                'items' => [],
                'errors' => ['Error import file: ' . $e->getMessage()],
            ], 422);
        }
    }
}

