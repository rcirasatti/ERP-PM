<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

/**
 * Import Bill of Quantities (BoQ) dari Excel
 * Mengikuti pattern yang sama dengan MaterialImport
 */
class BoqImport
{
    public array $items = [];
    public array $errors = [];
    public float $totalBiaya = 0;
    public float $totalMargin = 0;
    public float $grandTotal = 0;
    
    public int $success = 0;
    public array $importedItems = [];

    protected bool $previewMode = false;

    public function __construct(bool $previewMode = false)
    {
        $this->previewMode = $previewMode;
    }

    /**
     * Import BoQ dari file Excel
     * @param UploadedFile $file
     */
    public function import(UploadedFile $file): void
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Get header row dan skip
            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers);

            $rowNumber = 2; // Mulai dari 2 karena header di row 1

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }

                try {
                    $this->processRow($row, $headerMap, $rowNumber);
                } catch (\Exception $e) {
                    $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }

                $rowNumber++;
            }

            // Calculate grand total with PPN
            $this->grandTotal = ($this->totalBiaya + $this->totalMargin) * 1.11;
            $this->success = count($this->items);

        } catch (\Exception $e) {
            $this->errors[] = "Error membaca file: " . $e->getMessage();
        }
    }

    /**
     * Map header columns ke array indices
     */
    protected function mapHeaders(array $headers): array
    {
        $lowerHeaders = array_map(fn($h) => strtolower(trim($h ?? '')), $headers);
        
        return [
            'kode' => array_search('kode', $lowerHeaders),
            'nama' => array_search('nama', $lowerHeaders),
            'satuan' => array_search('satuan', $lowerHeaders),
            'jumlah' => array_search('jumlah', $lowerHeaders),
            'harga_satuan' => array_search('harga_satuan', $lowerHeaders),
            'persentase_margin' => array_search('persentase_margin', $lowerHeaders),
        ];
    }

    /**
     * Process single row
     */
    protected function processRow(array $row, array $headerMap, int $rowNumber): void
    {
        // Extract values dengan null coalescing
        $kode = trim($row[$headerMap['kode']] ?? '');
        $nama = trim($row[$headerMap['nama']] ?? '');
        $satuan = trim($row[$headerMap['satuan']] ?? '');
        $jumlah = (int)($row[$headerMap['jumlah']] ?? 0);
        $hargaSatuan = (float)($row[$headerMap['harga_satuan']] ?? 0);
        $persentaseMargin = (float)($row[$headerMap['persentase_margin']] ?? 0);

        // Validation
        if (!$kode || !$nama) {
            throw new \Exception("Kode dan Nama wajib diisi");
        }

        if ($jumlah <= 0) {
            throw new \Exception("Jumlah harus > 0");
        }

        if ($hargaSatuan < 0) {
            throw new \Exception("Harga satuan tidak boleh negatif");
        }

        if ($persentaseMargin < 0 || $persentaseMargin > 100) {
            throw new \Exception("Persentase margin harus 0-100");
        }

        // Calculate values
        $hargaAsli = $hargaSatuan;
        $marginPerUnit = $hargaAsli * ($persentaseMargin / 100);
        $hargaJual = $hargaAsli + $marginPerUnit;

        $totalBiayaItem = $hargaAsli * $jumlah;
        $totalMarginItem = $marginPerUnit * $jumlah;

        $item = [
            'kode' => $kode,
            'nama' => $nama,
            'satuan' => $satuan,
            'jumlah' => $jumlah,
            'harga_asli' => $hargaAsli,
            'harga_jual' => $hargaJual,
            'persentase_margin' => $persentaseMargin,
            'total_biaya_item' => $totalBiayaItem,
            'total_margin_item' => $totalMarginItem,
        ];

        if ($this->previewMode) {
            // Preview mode - collect all items without saving
            $this->items[] = $item;
        } else {
            // Import mode - prepare for saving
            $this->importedItems[] = $item;
            $this->items[] = $item;
        }

        $this->totalBiaya += $totalBiayaItem;
        $this->totalMargin += $totalMarginItem;
    }

    /**
     * Get summary untuk display
     */
    public function getSummary(): array
    {
        return [
            'total_items' => count($this->items),
            'total_biaya' => $this->totalBiaya,
            'total_margin' => $this->totalMargin,
            'subtotal' => $this->totalBiaya + $this->totalMargin,
            'ppn_11_percent' => ($this->totalBiaya + $this->totalMargin) * 0.11,
            'grand_total' => $this->grandTotal,
            'item_count' => count($this->items),
            'error_count' => count($this->errors),
        ];
    }
}

