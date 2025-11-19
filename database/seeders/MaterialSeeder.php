<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            [
                'supplier_id' => 1,
                'nama' => 'Besi Beton SNI 10mm',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'batang',
                'harga' => 75000,
            ],
            [
                'supplier_id' => 1,
                'nama' => 'Besi Beton SNI 12mm',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'batang',
                'harga' => 95000,
            ],
            [
                'supplier_id' => 2,
                'nama' => 'Kabel Listrik 2.5mm',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'roll',
                'harga' => 350000,
            ],
            [
                'supplier_id' => 2,
                'nama' => 'Panel Listrik 3 Phase',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'unit',
                'harga' => 2500000,
            ],
            [
                'supplier_id' => 3,
                'nama' => 'Semen Portland 50kg',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'sak',
                'harga' => 85000,
            ],
            [
                'supplier_id' => 3,
                'nama' => 'Pasir Beton',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'm3',
                'harga' => 350000,
            ],
            [
                'supplier_id' => 4,
                'nama' => 'Pipa PVC 3 inch',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'batang',
                'harga' => 125000,
            ],
            [
                'supplier_id' => 4,
                'nama' => 'Pipa Besi 2 inch',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'batang',
                'harga' => 250000,
            ],
            [
                'supplier_id' => 5,
                'nama' => 'Panel Surya 400W',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'unit',
                'harga' => 5000000,
            ],
            [
                'supplier_id' => 5,
                'nama' => 'Inverter Hybrid 10kW',
                'type' => 'BARANG',
                'track_inventory' => true,
                'satuan' => 'unit',
                'harga' => 25000000,
            ],
            [
                'supplier_id' => null,
                'nama' => 'Pasang Alat',
                'type' => 'JASA',
                'track_inventory' => false,
                'satuan' => 'Orang',
                'harga' => 1000000,
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
