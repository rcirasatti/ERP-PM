<?php

namespace Database\Seeders;

use App\Models\Proyek;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyekSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proyek::create([
            'nama' => 'energi hijau',
            'deskripsi' => 'kdakoskdwkmqldw',
            'client_id' => 5,
            'penawaran_id' => 1,
            'lokasi' => 'Jakarta Test',
            'tanggal_mulai' => '2025-11-11',
            'tanggal_selesai' => '2025-11-19',
            'status' => 'instalasi',
            'persentase_progres' => 50.0,
        ]);
    }
}
