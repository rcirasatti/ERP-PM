<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penawaran;
use App\Models\ItemPenawaran;
use App\Models\Material;
use App\Models\Client;

class PenawaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data with foreign key constraints disabled
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ItemPenawaran::truncate();
        Penawaran::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get sample client and materials
        $client = Client::first();
        $materials = Material::take(2)->get();

        if (!$client || $materials->count() < 2) {
            echo "Error: Pastikan ada minimal 1 client dan 2 material di database!\n";
            return;
        }

        // Create sample penawaran 1
        $penawaran1 = Penawaran::create([
            'no_penawaran' => 'PW-2025-11-001',
            'client_id' => $client->id,
            'tanggal' => now(),
            'status' => 'draft',
            'total_biaya' => 0, // Will be calculated
            'total_margin' => 0, // Will be calculated
        ]);

        // Create items for penawaran 1
        $material1 = $materials[0];
        $jumlah1 = 10;
        $margin1 = 10; // 10%
        $hargaJual1 = $material1->harga + ($material1->harga * $margin1 / 100);
        
        ItemPenawaran::create([
            'penawaran_id' => $penawaran1->id,
            'material_id' => $material1->id,
            'jumlah' => $jumlah1,
            'harga_asli' => $material1->harga,
            'persentase_margin' => $margin1,
            'harga_jual' => $hargaJual1,
        ]);

        $material2 = $materials[1];
        $jumlah2 = 5;
        $margin2 = 15; // 15%
        $hargaJual2 = $material2->harga + ($material2->harga * $margin2 / 100);
        
        ItemPenawaran::create([
            'penawaran_id' => $penawaran1->id,
            'material_id' => $material2->id,
            'jumlah' => $jumlah2,
            'harga_asli' => $material2->harga,
            'persentase_margin' => $margin2,
            'harga_jual' => $hargaJual2,
        ]);

        // Calculate totals for penawaran 1
        $totalBiaya1 = ($hargaJual1 * $jumlah1) + ($hargaJual2 * $jumlah2);
        $totalMargin1 = (($hargaJual1 - $material1->harga) * $jumlah1) + (($hargaJual2 - $material2->harga) * $jumlah2);
        
        $penawaran1->update([
            'total_biaya' => $totalBiaya1,
            'total_margin' => $totalMargin1,
        ]);

        // Create sample penawaran 2
        $penawaran2 = Penawaran::create([
            'no_penawaran' => 'PW-2025-11-002',
            'client_id' => $client->id,
            'tanggal' => now()->subDays(5),
            'status' => 'disetujui',
            'total_biaya' => 0,
            'total_margin' => 0,
        ]);

        // Create items for penawaran 2
        $jumlah3 = 20;
        $margin3 = 20; // 20%
        $hargaJual3 = $material1->harga + ($material1->harga * $margin3 / 100);
        
        ItemPenawaran::create([
            'penawaran_id' => $penawaran2->id,
            'material_id' => $material1->id,
            'jumlah' => $jumlah3,
            'harga_asli' => $material1->harga,
            'persentase_margin' => $margin3,
            'harga_jual' => $hargaJual3,
        ]);

        // Calculate totals for penawaran 2
        $totalBiaya2 = $hargaJual3 * $jumlah3;
        $totalMargin2 = ($hargaJual3 - $material1->harga) * $jumlah3;
        
        $penawaran2->update([
            'total_biaya' => $totalBiaya2,
            'total_margin' => $totalMargin2,
        ]);

        echo "Sample penawaran data created successfully!\n";
        echo "- Penawaran 1: {$penawaran1->no_penawaran} (2 items, Total: Rp " . number_format($totalBiaya1, 0, ',', '.') . ")\n";
        echo "- Penawaran 2: {$penawaran2->no_penawaran} (1 item, Total: Rp " . number_format($totalBiaya2, 0, ',', '.') . ")\n";
    }
}
