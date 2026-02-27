<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Import Bill of Quantities (BoQ) dari Excel
 * Format yang diharapkan sama seperti inventory (Material Template)
 * 
 * Kolom yang wajib: kode, nama, satuan, jumlah, harga_satuan
 * Optional: persentase_margin (default 0)
 */
class BoqImport implements ToCollection, WithHeadingRow
{
    public $items = [];
    public $errors = [];
    public $totalBiaya = 0;
    public $totalMargin = 0;
    public $grandTotal = 0;

    public function collection(Collection $rows)
    {
        $rowNumber = 2; // Start from 2 (after header)

        foreach ($rows as $row) {
            try {
                // Skip empty rows
                if (empty($row['kode']) || empty($row['nama'])) {
                    continue;
                }

                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $satuan = trim($row['satuan'] ?? '');
                $jumlah = (int)($row['jumlah'] ?? 0);
                $hargaSatuan = (float)($row['harga_satuan'] ?? 0);
                $persentaseMargin = (float)($row['persentase_margin'] ?? 0);

                // Validation
                if (!$kode || !$nama) {
                    $this->errors[] = "Row {$rowNumber}: Kode dan Nama wajib diisi";
                    $rowNumber++;
                    continue;
                }

                if ($jumlah <= 0) {
                    $this->errors[] = "Row {$rowNumber}: Jumlah harus > 0";
                    $rowNumber++;
                    continue;
                }

                if ($hargaSatuan < 0) {
                    $this->errors[] = "Row {$rowNumber}: Harga satuan tidak boleh negatif";
                    $rowNumber++;
                    continue;
                }

                if ($persentaseMargin < 0 || $persentaseMargin > 100) {
                    $this->errors[] = "Row {$rowNumber}: Persentase margin harus 0-100";
                    $rowNumber++;
                    continue;
                }

                // Calculate values
                $hargaAsli = $hargaSatuan;
                $marginPerUnit = $hargaAsli * ($persentaseMargin / 100);
                $hargaJual = $hargaAsli + $marginPerUnit;

                $totalBiayaItem = $hargaAsli * $jumlah;
                $totalMarginItem = $marginPerUnit * $jumlah;

                $this->items[] = [
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

                $this->totalBiaya += $totalBiayaItem;
                $this->totalMargin += $totalMarginItem;

            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }

            $rowNumber++;
        }

        // Calculate grand total with PPN (11%)
        $this->grandTotal = ($this->totalBiaya + $this->totalMargin) * 1.11;
    }

    /**
     * Get summary data untuk display
     */
    public function getSummary()
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
