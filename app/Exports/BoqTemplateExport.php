<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Export template BoQ untuk user
 * Format: Kode | Nama | Satuan | Jumlah | Harga Satuan | Persentase Margin
 */
class BoqTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        // Sample data (3 baris contoh yang bisa diedit user)
        return new Collection([
            [
                'MAT001',
                'Batu Bata',
                'pcs',
                50000,
                2000,
                10,
            ],
            [
                'MAT002',
                'Semen',
                'kg',
                20000,
                1500,
                10,
            ],
            [
                'JAR001',
                'Jasa Tukang',
                'hari',
                30,
                350000,
                15,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'satuan',
            'jumlah',
            'harga_satuan',
            'persentase_margin',
        ];
    }

    public function styles($sheet)
    {
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

        return $sheet;
    }
}
