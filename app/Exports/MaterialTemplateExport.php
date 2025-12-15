<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class MaterialTemplateExport
{
    public function download(string $filename = 'template_material.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers (tanpa kolom Jumlah karena dihitung otomatis dari Harga x Qty)
        $headers = ['No', 'Kategori', 'Kode', 'Item', 'Satuan', 'Supplier', 'Harga', 'Qty'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Sample data - menggunakan angka tanpa format agar Excel mengenali sebagai number
        $samples = [
            [1, 'BARANG', 'BHMA101', 'Besi Plat 10mm', 'Pcs', 'PT Besi Makmur', 50000, 10],
            [2, 'BARANG', 'BHMA102', 'Semen Putih', 'Kg', 'PT Semen Indonesia', 25000, 100],
            [3, 'JASA', 'JHMA101', 'Jasa Pemasangan', 'Jam', '', 150000, 0],
            [4, 'TOL', 'THMA101', 'Tol Jakarta-Surabaya', 'Pcs', '', 500000, 0],
        ];
        
        $row = 2;
        foreach ($samples as $sample) {
            // Set nilai dengan tipe data yang tepat
            $sheet->setCellValue('A' . $row, $sample[0]); // No (number)
            $sheet->setCellValue('B' . $row, $sample[1]); // Kategori (string)
            $sheet->setCellValue('C' . $row, $sample[2]); // Kode (string)
            $sheet->setCellValue('D' . $row, $sample[3]); // Item (string)
            $sheet->setCellValue('E' . $row, $sample[4]); // Satuan (string)
            $sheet->setCellValue('F' . $row, $sample[5]); // Supplier (string)
            $sheet->setCellValueExplicit('G' . $row, $sample[6], DataType::TYPE_NUMERIC); // Harga (number)
            $sheet->setCellValueExplicit('H' . $row, $sample[7], DataType::TYPE_NUMERIC); // Qty (number)
            $row++;
        }
        
        // Column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        
        // Header styling
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Data rows styling
        $sheet->getStyle('A2:H5')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Number format for currency column (Harga)
        $sheet->getStyle('G2:G100')->getNumberFormat()->setFormatCode('#,##0');
        
        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        // Return response
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
