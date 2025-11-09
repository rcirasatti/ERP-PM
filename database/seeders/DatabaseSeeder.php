<?php

namespace Database\Seeders;

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
        ]);

        // Seed entities that depend on suppliers and clients
        $this->call([
            MaterialSeeder::class,
            PenawaranSeeder::class,
        ]);

        // Seed inventory that depends on materials
        $this->call([
            InventorySeeder::class,
            ItemPenawaranSeeder::class,
        ]);

        // Seed projects that depend on clients and penawaran
        $this->call([
            ProyekSeeder::class,
        ]);
    }
}
