<?php

namespace Database\Seeders;

use App\Models\Tugas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TugasSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tugas::create([
            'proyek_id' => 1,
            'nama' => 'okey kak',
            'selesai' => true,
            'ditugaskan_ke' => null,
        ]);

        Tugas::create([
            'proyek_id' => 1,
            'nama' => 'nggatau',
            'selesai' => false,
            'ditugaskan_ke' => null,
        ]);
    }
}
