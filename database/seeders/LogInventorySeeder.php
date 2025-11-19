<?php

namespace Database\Seeders;

use App\Models\LogInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogInventorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LogInventory::create([
            'material_id' => 3,
            'jenis' => 'keluar',
            'jumlah' => 3,
            'tanggal' => '2025-11-19',
            'proyek_id' => 1,
            'catatan' => 'Pengurangan untuk proyek Energi Hijau',
            'dibuat_oleh' => 1,
        ]);

        LogInventory::create([
            'material_id' => 9,
            'jenis' => 'keluar',
            'jumlah' => 2,
            'tanggal' => '2025-11-19',
            'proyek_id' => 1,
            'catatan' => 'Pengurangan untuk proyek Energi Hijau',
            'dibuat_oleh' => 1,
        ]);
    }
}
