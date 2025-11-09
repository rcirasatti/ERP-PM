<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama' => 'PT Sinar Baja Terpadu',
                'kontak' => 'Budi Santoso',
                'email' => 'budi@sinarbaja.com',
                'telepon' => '0812-3456-7890',
                'alamat' => 'Jl. Industri No. 15, Jakarta',
            ],
            [
                'nama' => 'CV Maju Jaya Electronics',
                'kontak' => 'Rini Kusuma',
                'email' => 'rini@majujaya.co.id',
                'telepon' => '0823-4567-8901',
                'alamat' => 'Jl. Komponen No. 42, Surabaya',
            ],
            [
                'nama' => 'PT Multiguna Supplier',
                'kontak' => 'Ahmad Wijaya',
                'email' => 'ahmad@multiguna.net',
                'telepon' => '0834-5678-9012',
                'alamat' => 'Jl. Perdagangan No. 88, Bandung',
            ],
            [
                'nama' => 'Toko Material Bangunan Sejahtera',
                'kontak' => 'Siti Nurhaliza',
                'email' => 'siti@tokomaterial.id',
                'telepon' => '0845-6789-0123',
                'alamat' => 'Jl. Bangunan No. 23, Medan',
            ],
            [
                'nama' => 'PT Logistik Nusantara',
                'kontak' => 'Eka Putra',
                'email' => 'eka@logistiknusantara.com',
                'telepon' => '0856-7890-1234',
                'alamat' => 'Jl. Logistik No. 99, Semarang',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
