<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Import Bill of Quantities (BoQ) dari Excel
 * Menggunakan PhpSpreadsheet untuk parsing Excel
 */
class BoqImport
{
    public $items = [];
    public $errors = [];
    public $totalBiaya = 0;
    public $totalMargin = 0;
    public $grandTotal = 0;

    public function parse(UploadedFile $file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get header row
            $headers = [];
            foreach ($worksheet->getRowIterator(1, 1) as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    if (!is_null($cell->getValue())) {
                        $headers[] = strtolower(trim($cell->getValue()));
                    }
                }
            }
            
            $kodelIdx = array_search('kode', $headers);
            $namaIdx = array_search('nama', $headers);
            $satuanIdx = array_search('satuan', $headers);
            $jumlahIdx = array_search('jumlah', $headers);
            $hargaSatuanIdx = array_search('harga_satuan', $headers);
            $marginIdx = array_search('persentase_margin', $headers);
            
            $rowNumber = 2;
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellArray = [];
                foreach ($row->getCellIterator() as $cell) {
                    $cellArray[] = $cell->getValue();
                }
                
                if (empty($cellArray[0])) continue;
                
                try {
                    $kode = isset($cellArray[$kodelIdx]) ? trim($cellArray[$kodelIdx]) : '';
                    $nama = isset($cellArray[$namaIdx]) ? trim($cellArray[$namaIdx]) : '';
                    $satuan = isset($cellArray[$satuanIdx]) ? trim($cellArray[$satuanIdx]) : '';
                    $jumlah = isset($cellArray[$jumlahIdx]) ? (int)$cellArray[$jumlahIdx] : 0;
                    $hargaSatuan = isset($cellArray[$hargaSatuanIdx]) ? (float)$cellArray[$hargaSatuanIdx] : 0;
                    $persentaseMargin = isset($cellArray[$marginIdx]) ? (float)$cellArray[$marginIdx] : 0;
                    
                    if (!$kode || !$nama) {
                        $this->errors[] = "Row {$rowNumber}: Kode dan Nama wajib";
                        $rowNumber++;
                        continue;
                    }
                    if ($jumlah <= 0) {
                        $this->errors[] = "Row {$rowNumber}: Jumlah harus > 0";
                        $rowNumber++;
                        continue;
                    }
                    
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
            
            $this->grandTotal = ($this->totalBiaya + $this->totalMargin) * 1.11;
            
        } catch (\Exception $e) {
            $this->errors[] = "Error: " . $e->getMessage();
        }
    }

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
