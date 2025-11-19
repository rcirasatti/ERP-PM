<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengeluaran::create([
            'proyek_id' => 1,
            'tanggal' => '2025-11-18',
            'kategori' => 'gaji',
            'deskripsi' => 'kaoskdajsdk',
            'jumlah' => '2000000.00',
            'bukti_file' => 'pengeluaran/1763524130_ERP-Project-2025-11-09-021823.png',
            'dibuat_oleh' => 1,
        ]);
    }
}
