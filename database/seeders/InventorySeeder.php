<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            [
                'material_id' => 1,
                'stok' => 500,
            ],
            [
                'material_id' => 2,
                'stok' => 300,
            ],
            [
                'material_id' => 3,
                'stok' => 97,
            ],
            [
                'material_id' => 4,
                'stok' => 25,
            ],
            [
                'material_id' => 5,
                'stok' => 200,
            ],
            [
                'material_id' => 6,
                'stok' => 50,
            ],
            [
                'material_id' => 7,
                'stok' => 150,
            ],
            [
                'material_id' => 8,
                'stok' => 80,
            ],
            [
                'material_id' => 9,
                'stok' => 13,
            ],
            [
                'material_id' => 10,
                'stok' => 8,
            ],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
