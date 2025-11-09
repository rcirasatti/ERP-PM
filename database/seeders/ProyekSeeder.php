<?php

namespace Database\Seeders;

use App\Models\Proyek;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'nama' => 'Instalasi Panel Surya Gedung Kantor',
                'client_id' => 1,
                'penawaran_id' => 1,
                'lokasi' => 'Jl. Utama No. 5, Jakarta Selatan',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(30)->toDateString(),
                'status' => 'survei',
                'persentase_progres' => 25.50,
            ],
            [
                'nama' => 'Pembangunan Instalasi Listrik Pabrik',
                'client_id' => 2,
                'penawaran_id' => 2,
                'lokasi' => 'Jl. Permata No. 12, Depok',
                'tanggal_mulai' => now()->subDays(5)->toDateString(),
                'tanggal_selesai' => now()->addDays(45)->toDateString(),
                'status' => 'instalasi',
                'persentase_progres' => 60.00,
            ],
            [
                'nama' => 'Sistem Irigasi Otomatis Pertanian',
                'client_id' => 3,
                'penawaran_id' => 4,
                'lokasi' => 'Jl. Industri No. 67, Bekasi',
                'tanggal_mulai' => now()->subDays(15)->toDateString(),
                'tanggal_selesai' => now()->addDays(20)->toDateString(),
                'status' => 'pengujian',
                'persentase_progres' => 85.00,
            ],
        ];

        foreach ($projects as $project) {
            Proyek::create($project);
        }
    }
}
