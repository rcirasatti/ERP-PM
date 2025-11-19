<?php

namespace Database\Seeders;

use App\Models\ProyekBudget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyekBudgetSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProyekBudget::create([
            'proyek_id' => 1,
            'jumlah_rencana' => '15379050.00',
            'jumlah_realisasi' => '2000000.00',
        ]);
    }
}
