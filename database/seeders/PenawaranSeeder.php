<?php

namespace Database\Seeders;

use App\Models\Penawaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenawaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penawaran = [
            [
                'no_penawaran' => 'PWR-2025-001',
                'client_id' => 1,
                'tanggal' => now()->toDateString(),
                'status' => 'disetujui',
                'total_margin' => 48150000,
                'total_biaya' => 12425000,
            ],
            [
                'no_penawaran' => 'PWR-2025-002',
                'client_id' => 2,
                'tanggal' => now()->subDays(5)->toDateString(),
                'status' => 'disetujui',
                'total_margin' => 1234000,
                'total_biaya' => 8560000,
            ],
            [
                'no_penawaran' => 'PWR-2025-003',
                'client_id' => 3,
                'tanggal' => now()->subDays(10)->toDateString(),
                'status' => 'draft',
                'total_margin' => 1156500,
                'total_biaya' => 5671500,
            ],
            [
                'no_penawaran' => 'PWR-2025-004',
                'client_id' => 4,
                'tanggal' => now()->subDays(15)->toDateString(),
                'status' => 'disetujui',
                'total_margin' => 856500,
                'total_biaya' => 4276500,
            ],
            [
                'no_penawaran' => 'PWR-2025-005',
                'client_id' => 5,
                'tanggal' => now()->subDays(20)->toDateString(),
                'status' => 'ditolak',
                'total_margin' => 1435000,
                'total_biaya' => 7175000,
            ],
        ];

        foreach ($penawaran as $pnw) {
            Penawaran::create($pnw);
        }
    }
}
