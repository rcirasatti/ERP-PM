<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Export template BoQ untuk user
 * Format: Kode | Nama | Satuan | Jumlah | Harga Satuan | Persentase Margin
 * 
 * Menggunakan PhpOffice\PhpSpreadsheet directly untuk menghindari dependency issues
 */
class BoqTemplateExport
{
    /**
     * Generate dan download Excel template BoQ
     */
    public static function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('BoQ Template');
        
        // Header row
        $headers = ['kode', 'nama', 'satuan', 'jumlah', 'harga_satuan', 'persentase_margin'];
        $sheet->fromArray($headers, NULL, 'A1');
        
        // Sample data (3 baris contoh yang bisa diedit user)
        $sampleData = [
            ['MAT001', 'Batu Bata', 'pcs', 50000, 2000, 10],
            ['MAT002', 'Semen', 'kg', 20000, 1500, 10],
            ['JAR001', 'Jasa Tukang', 'hari', 30, 350000, 15],
        ];
        $sheet->fromArray($sampleData, NULL, 'A2');
        
        // Style header row
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Auto size columns
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        
        // Create writer and return
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_BoQ_Penawaran_' . date('Y-m-d_His') . '.xlsx';
        
        // Output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');
        
        $writer->save('php://output');
        exit;
    }
}
