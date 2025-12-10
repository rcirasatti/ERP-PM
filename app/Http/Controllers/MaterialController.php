<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Inventory;
use App\Models\Supplier;
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
        $materials = Material::with('supplier', 'inventory')->orderBy('created_at', 'desc')->get();
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
     * Export template CSV for material import
     */
    public function exportTemplate()
    {
        $headers = ['No', 'Kategori', 'Item', 'Satuan', 'Supplier', 'Harga', 'Qty', 'Jumlah'];
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            // Add sample rows with examples
            $samples = [
                ['1', 'BARANG', 'Besi Plat 10mm', 'Pcs', 'PT Besi Makmur', '50000', '10', '500000'],
                ['2', 'BARANG', 'Semen Putih', 'Kg', 'PT Semen Indonesia', '25000', '100', '2500000'],
                ['3', 'JASA', 'Jasa Pemasangan', 'Jam', '', '150000', '0', '0'],
                ['4', 'TOL', 'Tol Jakarta-Surabaya', 'Pcs', '', '500000', '0', '0'],
            ];
            
            foreach ($samples as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_material.csv"
        ]);
    }

    /**
     * Preview import CSV file to detect duplicates and price changes
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $duplicates = [];
        $priceChanges = [];
        $newItems = [];
        $errors = [];
        $rowNumber = 1;
        
        if (($handle = fopen($path, 'r')) !== FALSE) {
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }
                
                try {
                    if (count($data) < 8) {
                        throw new \Exception("Jumlah kolom tidak sesuai");
                    }
                    
                    $kategori = trim($data[1] ?? '');
                    $item = trim($data[2] ?? '');
                    $satuan = trim($data[3] ?? '');
                    $supplier_name = trim($data[4] ?? '');
                    $harga = $data[5] ?? 0;
                    $qty = $data[6] ?? 0;
                    
                    if (empty($kategori) || empty($item) || empty($satuan) || empty($harga)) {
                        throw new \Exception("Data tidak lengkap");
                    }
                    
                    $kategori = strtoupper($kategori);
                    $validTypes = array_keys(Material::getTypes());
                    if (!in_array($kategori, $validTypes)) {
                        throw new \Exception("Kategori '{$kategori}' tidak valid");
                    }
                    
                    if (!is_numeric($harga) || $harga < 0) {
                        throw new \Exception("Harga harus angka positif");
                    }
                    
                    // Find or create supplier
                    $supplier_id = null;
                    if ($kategori === Material::TYPE_BARANG && !empty($supplier_name)) {
                        $supplier = Supplier::where('nama', $supplier_name)->first();
                        if (!$supplier) {
                            $supplier = Supplier::create([
                                'nama' => $supplier_name,
                                'kontak' => '',
                                'email' => '',
                                'telepon' => '',
                                'alamat' => '',
                            ]);
                        }
                        $supplier_id = $supplier->id;
                    }
                    
                    // Check if material exists
                    $query = Material::where('nama', $item);
                    if ($supplier_id) {
                        $query->where('supplier_id', $supplier_id);
                    } else {
                        $query->whereNull('supplier_id');
                    }
                    $existing = $query->first();
                    
                    if ($existing) {
                        $qtyValue = ($kategori === Material::TYPE_BARANG && !empty($qty)) ? (float) $qty : 0;
                        $inventory = $existing->inventory;
                        $currentStok = $inventory?->stok ?? 0;
                        $newStok = $currentStok + $qtyValue;
                        
                        $priceChanged = $existing->harga != $harga;
                        
                        $duplicates[] = [
                            'row' => $rowNumber,
                            'nama' => $item,
                            'supplier' => $supplier_name ?: 'Tidak ada',
                            'oldPrice' => $existing->harga,
                            'newPrice' => (float) $harga,
                            'priceChanged' => $priceChanged,
                            'currentStok' => $currentStok,
                            'addStok' => $qtyValue,
                            'newStok' => $newStok,
                            'materialId' => $existing->id,
                        ];
                    } else {
                        $newItems[] = [
                            'row' => $rowNumber,
                            'nama' => $item,
                            'supplier' => $supplier_name ?: 'Tidak ada',
                            'harga' => (float) $harga,
                            'qty' => ($kategori === Material::TYPE_BARANG && !empty($qty)) ? (float) $qty : 0,
                        ];
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }
            
            fclose($handle);
        }
        
        return response()->json([
            'duplicates' => $duplicates,
            'newItems' => $newItems,
            'errors' => $errors,
            'totalDuplicates' => count($duplicates),
            'totalNew' => count($newItems),
        ]);
    }

    /**
     * Import materials from CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $errors = [];
        $success = 0;
        $newItemsAdded = 0;
        $stokAdded = 0;
        $pricesUpdated = 0;
        $rowNumber = 1;
        $importedItems = [];
        
        if (($handle = fopen($path, 'r')) !== FALSE) {
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }
                
                try {
                    // Ensure we have enough columns
                    if (count($data) < 8) {
                        throw new \Exception("Jumlah kolom tidak sesuai (minimum 8 kolom diperlukan)");
                    }
                    
                    // Map CSV columns to variables
                    $no = $data[0] ?? '';
                    $kategori = trim($data[1] ?? '');
                    $item = trim($data[2] ?? '');
                    $satuan = trim($data[3] ?? '');
                    $supplier_name = trim($data[4] ?? '');
                    $harga = $data[5] ?? 0;
                    $qty = $data[6] ?? 0;
                    $jumlah = $data[7] ?? 0;
                    
                    // Validation
                    if (empty($kategori)) {
                        throw new \Exception("Kategori tidak boleh kosong (Kolom 2)");
                    }
                    
                    if (empty($item)) {
                        throw new \Exception("Item tidak boleh kosong (Kolom 3)");
                    }
                    
                    if (empty($satuan)) {
                        throw new \Exception("Satuan tidak boleh kosong (Kolom 4)");
                    }
                    
                    if (empty($harga) || !is_numeric($harga) || $harga < 0) {
                        throw new \Exception("Harga harus berupa angka positif (Kolom 7)");
                    }
                    
                    // Normalize category
                    $kategori = strtoupper($kategori);
                    $validTypes = array_keys(Material::getTypes());
                    if (!in_array($kategori, $validTypes)) {
                        throw new \Exception("Kategori '{$kategori}' tidak valid. Gunakan: " . implode(', ', $validTypes));
                    }
                    
                    // Normalize qty based on category
                    $qtyValue = 0;
                    if ($kategori === Material::TYPE_BARANG && !empty($qty)) {
                        $qtyValue = (float) $qty;
                        if ($qtyValue < 0) {
                            $qtyValue = 0;
                        }
                    }
                    
                    // Find or create supplier - HANYA untuk BARANG
                    $supplier_id = null;
                    if ($kategori === Material::TYPE_BARANG && !empty($supplier_name)) {
                        $supplier = Supplier::where('nama', $supplier_name)->first();
                        if (!$supplier) {
                            $supplier = Supplier::create([
                                'nama' => $supplier_name,
                                'kontak' => '',
                                'email' => '',
                                'telepon' => '',
                                'alamat' => '',
                            ]);
                        }
                        $supplier_id = $supplier->id;
                    } else if ($kategori !== Material::TYPE_BARANG && !empty($supplier_name)) {
                        throw new \Exception("Supplier '{$supplier_name}' diabaikan karena kategori {$kategori} tidak memerlukan supplier");
                    }
                    
                    // Prepare material data
                    $materialData = [
                        'nama' => $item,
                        'satuan' => $satuan,
                        'harga' => (float) $harga,
                        'type' => $kategori,
                        'track_inventory' => ($kategori === Material::TYPE_BARANG),
                        'supplier_id' => $supplier_id,
                    ];
                    
                    // Check if material already exists by name & supplier
                    $query = Material::where('nama', $item);
                    if ($supplier_id) {
                        $query->where('supplier_id', $supplier_id);
                    } else {
                        $query->whereNull('supplier_id');
                    }
                    $existing = $query->first();
                    
                    if ($existing) {
                        // Check if harga berubah
                        $priceChanged = $existing->harga != $materialData['harga'];
                        
                        // Update material dengan harga baru jika berbeda
                        if ($priceChanged) {
                            $existing->update($materialData);
                            $pricesUpdated++;
                        }
                        
                        $material = $existing;
                        $isNew = false;
                    } else {
                        // Create new material
                        $material = Material::create($materialData);
                        $newItemsAdded++;
                        $isNew = true;
                    }
                    
                    // Handle inventory for BARANG type
                    if ($kategori === Material::TYPE_BARANG) {
                        $inventory = Inventory::where('material_id', $material->id)->first();
                        
                        if ($inventory) {
                            // TAMBAHKAN stok, jangan replace
                            if ($qtyValue > 0) {
                                $newStok = $inventory->stok + $qtyValue;
                                $inventory->update(['stok' => $newStok]);
                                $stokAdded++;
                                
                                $importedItems[] = [
                                    'nama' => $item,
                                    'type' => 'stok_ditambah',
                                    'qty' => $qtyValue,
                                ];
                            }
                        } else if ($qtyValue > 0) {
                            // Create new inventory only if qty > 0
                            Inventory::create([
                                'material_id' => $material->id,
                                'stok' => $qtyValue,
                            ]);
                            
                            if ($isNew) {
                                $importedItems[] = [
                                    'nama' => $item,
                                    'type' => 'baru',
                                    'qty' => $qtyValue,
                                ];
                            }
                        } elseif ($isNew && $qtyValue == 0) {
                            $importedItems[] = [
                                'nama' => $item,
                                'type' => 'baru',
                                'qty' => 0,
                            ];
                        }
                    } else {
                        if ($isNew) {
                            $importedItems[] = [
                                'nama' => $item,
                                'type' => 'baru',
                                'qty' => 0,
                            ];
                        }
                    }
                    
                    $success++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }
            
            fclose($handle);
        }
        
        // Return JSON response
        return response()->json([
            'success' => true,
            'stats' => [
                'totalProcessed' => $success,
                'newItems' => $newItemsAdded,
                'stokAdded' => $stokAdded,
                'pricesUpdated' => $pricesUpdated,
            ],
            'items' => $importedItems,
            'errors' => $errors,
        ]);
    }
}

