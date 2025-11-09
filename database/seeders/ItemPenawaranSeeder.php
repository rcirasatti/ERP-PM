<?php

namespace Database\Seeders;

use App\Models\ItemPenawaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemPenawaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'penawaran_id' => 1,
                'material_id' => 1,
                'jumlah' => 100,
                'harga_asli' => 75000,
                'persentase_margin' => 15,
                'harga_jual' => 86250,
            ],
            [
                'penawaran_id' => 1,
                'material_id' => 3,
                'jumlah' => 10,
                'harga_asli' => 350000,
                'persentase_margin' => 20,
                'harga_jual' => 420000,
            ],
            [
                'penawaran_id' => 2,
                'material_id' => 5,
                'jumlah' => 50,
                'harga_asli' => 85000,
                'persentase_margin' => 10,
                'harga_jual' => 93500,
            ],
            [
                'penawaran_id' => 2,
                'material_id' => 6,
                'jumlah' => 20,
                'harga_asli' => 350000,
                'persentase_margin' => 12,
                'harga_jual' => 392000,
            ],
            [
                'penawaran_id' => 3,
                'material_id' => 7,
                'jumlah' => 30,
                'harga_asli' => 125000,
                'persentase_margin' => 18,
                'harga_jual' => 147500,
            ],
            [
                'penawaran_id' => 3,
                'material_id' => 8,
                'jumlah' => 15,
                'harga_asli' => 250000,
                'persentase_margin' => 15,
                'harga_jual' => 287500,
            ],
        ];

        foreach ($items as $item) {
            ItemPenawaran::create($item);
        }
    }
}
