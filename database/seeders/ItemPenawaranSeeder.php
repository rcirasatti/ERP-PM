<?php

namespace Database\Seeders;

use App\Models\ItemPenawaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemPenawaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemPenawaran::create([
            'penawaran_id' => 1,
            'material_id' => 3,
            'jumlah' => 3,
            'harga_asli' => '350000.00',
            'persentase_margin' => '10.00',
            'harga_jual' => '385000.00',
        ]);

        ItemPenawaran::create([
            'penawaran_id' => 1,
            'material_id' => 9,
            'jumlah' => 2,
            'harga_asli' => '5000000.00',
            'persentase_margin' => '5.00',
            'harga_jual' => '5250000.00',
        ]);

        ItemPenawaran::create([
            'penawaran_id' => 1,
            'material_id' => 11,
            'jumlah' => 2,
            'harga_asli' => '1000000.00',
            'persentase_margin' => '10.00',
            'harga_jual' => '1100000.00',
        ]);
    }
}
