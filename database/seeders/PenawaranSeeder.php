<?php

namespace Database\Seeders;

use App\Models\Penawaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenawaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penawaran::create([
            'no_penawaran' => 'PW-2025-11-001',
            'client_id' => 5,
            'tanggal' => '2025-11-18',
            'status' => 'disetujui',
            'total_margin' => '805000.00',
            'ppn' => 1524050.0,
            'grand_total_with_ppn' => 15379050.00,
            'total_biaya' => '13050000.00',
        ]);
    }
}
