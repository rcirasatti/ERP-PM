<?php

namespace Database\Seeders;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed base entities first (no FK dependencies)
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            ClientSeeder::class,
            MaterialSeeder::class,
            InventorySeeder::class
        ]);
    
    }
}
