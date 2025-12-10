<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\Supplier;
use App\Models\Inventory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

class MaterialImport
{
    public array $errors = [];
    public int $success = 0;
    public int $newItemsAdded = 0;
    public int $stokAdded = 0;
    public int $pricesUpdated = 0;
    public array $importedItems = [];

    protected bool $previewMode = false;
    public array $duplicates = [];
    public array $newItems = [];

    public function __construct(bool $previewMode = false)
    {
        $this->previewMode = $previewMode;
    }

    public function import(UploadedFile $file): void
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Get header row
        $headers = array_shift($rows);
        $headerMap = $this->mapHeaders($headers);

        $rowNumber = 1; // Start from 1 since heading row is skipped

        foreach ($rows as $row) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $this->processRow($row, $headerMap, $rowNumber);
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            }
        }
    }

    protected function mapHeaders(array $headers): array
    {
        $map = [];
        $normalizedHeaders = array_map(function($h) {
            return strtolower(trim((string) $h));
        }, $headers);

        $headerNames = [
            'no' => ['no', 'nomor', 'number'],
            'kategori' => ['kategori', 'category', 'tipe', 'type'],
            'item' => ['item', 'nama', 'name', 'material'],
            'satuan' => ['satuan', 'unit', 'uom'],
            'supplier' => ['supplier', 'vendor', 'pemasok'],
            'harga' => ['harga', 'price', 'harga satuan'],
            'qty' => ['qty', 'quantity', 'stok'],
            // Kolom 'jumlah' tidak perlu karena dihitung otomatis dari harga x qty
        ];

        foreach ($headerNames as $key => $variants) {
            foreach ($variants as $variant) {
                $index = array_search($variant, $normalizedHeaders);
                if ($index !== false) {
                    $map[$key] = $index;
                    break;
                }
            }
        }

        return $map;
    }

    protected function processRow(array $row, array $headerMap, int $rowNumber): void
    {
        // Map Excel columns to variables
        $kategori = trim((string) ($row[$headerMap['kategori'] ?? 1] ?? ''));
        $item = trim((string) ($row[$headerMap['item'] ?? 2] ?? ''));
        $satuan = trim((string) ($row[$headerMap['satuan'] ?? 3] ?? ''));
        $supplier_name = trim((string) ($row[$headerMap['supplier'] ?? 4] ?? ''));
        $harga = $row[$headerMap['harga'] ?? 5] ?? 0;
        $qty = $row[$headerMap['qty'] ?? 6] ?? 0;

        // Validation
        if (empty($kategori)) {
            throw new \Exception("Kategori tidak boleh kosong");
        }

        if (empty($item)) {
            throw new \Exception("Item tidak boleh kosong");
        }

        if (empty($satuan)) {
            throw new \Exception("Satuan tidak boleh kosong");
        }

        // Clean and validate harga
        $harga = $this->cleanNumber($harga);
        if ($harga < 0) {
            throw new \Exception("Harga harus berupa angka positif");
        }

        // Normalize category
        $kategori = strtoupper($kategori);
        $validTypes = array_keys(Material::getTypes());
        if (!in_array($kategori, $validTypes)) {
            throw new \Exception("Kategori '{$kategori}' tidak valid. Gunakan: " . implode(', ', $validTypes));
        }

        // Normalize qty based on category
        $qtyValue = 0;
        if ($kategori === Material::TYPE_BARANG) {
            $qtyValue = $this->cleanNumber($qty);
            if ($qtyValue < 0) {
                $qtyValue = 0;
            }
        }

        // Find or create supplier - HANYA untuk BARANG
        $supplier_id = null;
        if ($kategori === Material::TYPE_BARANG && !empty($supplier_name)) {
            $supplier = Supplier::where('nama', $supplier_name)->first();
            
            if (!$this->previewMode && !$supplier) {
                $supplier = Supplier::create([
                    'nama' => $supplier_name,
                    'kontak' => '',
                    'email' => '',
                    'telepon' => '',
                    'alamat' => '',
                ]);
            }
            
            $supplier_id = $supplier?->id;
        }

        // Check if material exists
        $query = Material::where('nama', $item);
        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        } else {
            $query->whereNull('supplier_id');
        }
        $existing = $query->first();

        if ($this->previewMode) {
            $this->handlePreview($existing, $rowNumber, $item, $supplier_name, $harga, $qtyValue, $kategori);
        } else {
            $this->handleImport($existing, $item, $satuan, $harga, $kategori, $supplier_id, $qtyValue);
        }
    }

    protected function handlePreview($existing, int $rowNumber, string $item, string $supplier_name, float $harga, float $qtyValue, string $kategori): void
    {
        if ($existing) {
            $inventory = $existing->inventory;
            $currentStok = $inventory?->stok ?? 0;
            $newStok = $currentStok + $qtyValue;
            $priceChanged = $existing->harga != $harga;

            $this->duplicates[] = [
                'row' => $rowNumber,
                'nama' => $item,
                'supplier' => $supplier_name ?: 'Tidak ada',
                'oldPrice' => $existing->harga,
                'newPrice' => $harga,
                'priceChanged' => $priceChanged,
                'currentStok' => $currentStok,
                'addStok' => $qtyValue,
                'newStok' => $newStok,
                'materialId' => $existing->id,
            ];
        } else {
            $this->newItems[] = [
                'row' => $rowNumber,
                'nama' => $item,
                'supplier' => $supplier_name ?: 'Tidak ada',
                'harga' => $harga,
                'qty' => $qtyValue,
            ];
        }
    }

    protected function handleImport($existing, string $item, string $satuan, float $harga, string $kategori, ?int $supplier_id, float $qtyValue): void
    {
        $materialData = [
            'nama' => $item,
            'satuan' => $satuan,
            'harga' => $harga,
            'type' => $kategori,
            'track_inventory' => ($kategori === Material::TYPE_BARANG),
            'supplier_id' => $supplier_id,
        ];

        if ($existing) {
            $priceChanged = $existing->harga != $harga;

            if ($priceChanged) {
                $existing->update($materialData);
                $this->pricesUpdated++;
            }

            $material = $existing;
            $isNew = false;
        } else {
            $material = Material::create($materialData);
            $this->newItemsAdded++;
            $isNew = true;
        }

        // Handle inventory for BARANG type
        if ($kategori === Material::TYPE_BARANG) {
            $inventory = Inventory::where('material_id', $material->id)->first();

            if ($inventory) {
                if ($qtyValue > 0) {
                    $newStok = $inventory->stok + $qtyValue;
                    $inventory->update(['stok' => $newStok]);
                    $this->stokAdded++;

                    $this->importedItems[] = [
                        'nama' => $item,
                        'type' => 'stok_ditambah',
                        'qty' => $qtyValue,
                    ];
                }
            } else if ($qtyValue > 0) {
                Inventory::create([
                    'material_id' => $material->id,
                    'stok' => $qtyValue,
                ]);

                if ($isNew) {
                    $this->importedItems[] = [
                        'nama' => $item,
                        'type' => 'baru',
                        'qty' => $qtyValue,
                    ];
                }
            } elseif ($isNew && $qtyValue == 0) {
                $this->importedItems[] = [
                    'nama' => $item,
                    'type' => 'baru',
                    'qty' => 0,
                ];
            }
        } else {
            if ($isNew) {
                $this->importedItems[] = [
                    'nama' => $item,
                    'type' => 'baru',
                    'qty' => 0,
                ];
            }
        }

        $this->success++;
    }

    /**
     * Clean number from Excel cell value
     * Excel menyimpan angka sebagai numeric, bukan string dengan format
     * Tapi jika user edit dan save dari Excel, bisa jadi string dengan format lokal
     */
    protected function cleanNumber($value): float
    {
        // Jika sudah numeric (dari Excel), langsung return
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Jika null atau empty
        if ($value === null || $value === '') {
            return 0.0;
        }

        $stringValue = (string) $value;
        
        // Deteksi format Indonesia (titik sebagai ribuan, koma sebagai desimal)
        // Contoh: "1.500.000" atau "1.500.000,50"
        $hasDotThousand = preg_match('/\d{1,3}(\.\d{3})+/', $stringValue);
        
        if ($hasDotThousand) {
            // Format Indonesia: hapus titik ribuan, ganti koma desimal dengan titik
            $stringValue = str_replace('.', '', $stringValue);
            $stringValue = str_replace(',', '.', $stringValue);
        } else {
            // Format internasional atau tanpa separator: hapus koma ribuan
            $stringValue = str_replace(',', '', $stringValue);
        }
        
        // Hapus karakter non-numeric kecuali titik dan minus
        $stringValue = preg_replace('/[^0-9.\-]/', '', $stringValue);

        return (float) $stringValue;
    }
}
