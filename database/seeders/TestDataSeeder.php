<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Material;
use App\Models\Penawaran;
use App\Models\ItemPenawaran;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data - disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ItemPenawaran::truncate();
        Penawaran::truncate();
        Material::truncate();
        Client::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create test client
        $client = Client::create([
            'nama' => 'PT Test Client',
            'alamat' => 'Jl. Test No. 123, Jakarta',
            'kontak' => 'John Doe',
            'telepon' => '021-123456',
            'email' => 'test@client.com',
        ]);

        echo "✓ Created Client: {$client->id}\n";

        // Create test materials
        $materials = [
            ['kode' => 'MAT001', 'nama' => 'Batu Bata', 'type' => 'BARANG', 'satuan' => 'pcs', 'harga' => 2000, 'track_inventory' => false],
            ['kode' => 'MAT002', 'nama' => 'Semen Portland', 'type' => 'BARANG', 'satuan' => 'kg', 'harga' => 1500, 'track_inventory' => false],
            ['kode' => 'MAT003', 'nama' => 'Pasir Putih', 'type' => 'BARANG', 'satuan' => 'kg', 'harga' => 500, 'track_inventory' => false],
            ['kode' => 'JAR001', 'nama' => 'Jasa Tukang', 'type' => 'JASA', 'satuan' => 'hari', 'harga' => 350000, 'track_inventory' => false],
            ['kode' => 'JAR002', 'nama' => 'Jasa Supervisor', 'type' => 'JASA', 'satuan' => 'hari', 'harga' => 500000, 'track_inventory' => false],
        ];

        $materialIds = [];
        foreach ($materials as $mat) {
            $material = Material::create($mat);
            $materialIds[$mat['kode']] = $material->id;
            echo "✓ Created Material: {$mat['kode']} ({$material->id})\n";
        }

        // Create source penawaran with items (status=disetujui for history)
        $sourcePenawaran = Penawaran::create([
            'no_penawaran' => 'OFF-2026-001',
            'client_id' => $client->id,
            'tanggal' => now()->subDays(10),
            'status' => 'disetujui',
            'ai_status' => 'analyzed',
            'total_biaya' => 0,
            'total_margin' => 0,
            'ppn' => 0,
            'grand_total_with_ppn' => 0,
            'ai_prediksi_lr' => 0,
            'ai_prediksi_ma' => 0,
            'margin_status' => 'aman',
        ]);

        echo "✓ Created Source Penawaran: {$sourcePenawaran->id} ({$sourcePenawaran->no_penawaran})\n";

        // Create items for source penawaran
        $items = [
            ['material_id' => $materialIds['MAT001'], 'nama' => 'Batu Bata', 'satuan' => 'pcs', 'jumlah' => 50000, 'harga_asli' => 2000, 'persentase_margin' => 10],
            ['material_id' => $materialIds['MAT002'], 'nama' => 'Semen Portland', 'satuan' => 'kg', 'jumlah' => 20000, 'harga_asli' => 1500, 'persentase_margin' => 10],
            ['material_id' => $materialIds['MAT003'], 'nama' => 'Pasir Putih', 'satuan' => 'kg', 'jumlah' => 30000, 'harga_asli' => 500, 'persentase_margin' => 8],
            ['material_id' => $materialIds['JAR001'], 'nama' => 'Jasa Tukang', 'satuan' => 'hari', 'jumlah' => 30, 'harga_asli' => 350000, 'persentase_margin' => 15],
        ];

        $totalBiaya = 0;
        $totalMargin = 0;

        foreach ($items as $itemData) {
            $hargaAsli = $itemData['harga_asli'];
            $margin = $itemData['persentase_margin'];
            $hargaJual = $hargaAsli + ($hargaAsli * $margin / 100);
            
            $totalBiayaItem = $hargaAsli * $itemData['jumlah'];
            $totalMarginItem = ($hargaAsli * ($margin / 100)) * $itemData['jumlah'];
            
            $totalBiaya += $totalBiayaItem;
            $totalMargin += $totalMarginItem;
            
            ItemPenawaran::create([
                'penawaran_id' => $sourcePenawaran->id,
                'material_id' => $itemData['material_id'],
                'nama' => $itemData['nama'],
                'satuan' => $itemData['satuan'],
                'jumlah' => $itemData['jumlah'],
                'harga_asli' => $hargaAsli,
                'persentase_margin' => $margin,
                'harga_jual' => $hargaJual,
            ]);
        }

        // Calculate and update totals
        $subtotal = $totalBiaya + $totalMargin;
        $ppn = $subtotal * 0.11;
        $grandTotal = $subtotal + $ppn;

        $sourcePenawaran->update([
            'total_biaya' => $totalBiaya,
            'total_margin' => $totalMargin,
            'ppn' => $ppn,
            'grand_total_with_ppn' => $grandTotal,
            'ai_prediksi_lr' => $grandTotal,
            'ai_prediksi_ma' => $grandTotal,
        ]);

        echo "✓ Created Items in Source Penawaran: " . count($items) . " items\n";
        echo "  - Total Biaya: Rp " . number_format($totalBiaya, 0, ',', '.') . "\n";
        echo "  - Total Margin: Rp " . number_format($totalMargin, 0, ',', '.') . "\n";
        echo "  - PPN (11%): Rp " . number_format($ppn, 0, ',', '.') . "\n";
        echo "  - Grand Total: Rp " . number_format($grandTotal, 0, ',', '.') . "\n";

        // Create target penawaran (empty, for copying into)
        $targetPenawaran = Penawaran::create([
            'no_penawaran' => 'OFF-2026-002',
            'client_id' => $client->id,
            'tanggal' => now(),
            'status' => 'draft',
            'ai_status' => 'pending',
            'total_biaya' => 0,
            'total_margin' => 0,
            'ppn' => 0,
            'grand_total_with_ppn' => 0,
        ]);

        echo "✓ Created Target Penawaran: {$targetPenawaran->id} ({$targetPenawaran->no_penawaran})\n";

        // Create another approved penawaran for history/price trend testing
        $otherPenawaran = Penawaran::create([
            'no_penawaran' => 'OFF-2026-003',
            'client_id' => $client->id,
            'tanggal' => now()->subDays(30),
            'status' => 'disetujui',
            'ai_status' => 'analyzed',
            'total_biaya' => 0,
            'total_margin' => 0,
            'ppn' => 0,
            'grand_total_with_ppn' => 0,
        ]);

        // Add items with slightly different pricing (for price trend testing)
        $otherItems = [
            ['material_id' => $materialIds['MAT001'], 'nama' => 'Batu Bata', 'satuan' => 'pcs', 'jumlah' => 40000, 'harga_asli' => 1900, 'persentase_margin' => 9],
            ['material_id' => $materialIds['JAR001'], 'nama' => 'Jasa Tukang', 'satuan' => 'hari', 'jumlah' => 25, 'harga_asli' => 330000, 'persentase_margin' => 14],
        ];

        $totalBiaya2 = 0;
        $totalMargin2 = 0;

        foreach ($otherItems as $itemData) {
            $hargaAsli = $itemData['harga_asli'];
            $margin = $itemData['persentase_margin'];
            $hargaJual = $hargaAsli + ($hargaAsli * $margin / 100);
            
            $totalBiayaItem = $hargaAsli * $itemData['jumlah'];
            $totalMarginItem = ($hargaAsli * ($margin / 100)) * $itemData['jumlah'];
            
            $totalBiaya2 += $totalBiayaItem;
            $totalMargin2 += $totalMarginItem;
            
            ItemPenawaran::create([
                'penawaran_id' => $otherPenawaran->id,
                'material_id' => $itemData['material_id'],
                'nama' => $itemData['nama'],
                'satuan' => $itemData['satuan'],
                'jumlah' => $itemData['jumlah'],
                'harga_asli' => $hargaAsli,
                'persentase_margin' => $margin,
                'harga_jual' => $hargaJual,
            ]);
        }

        $subtotal2 = $totalBiaya2 + $totalMargin2;
        $ppn2 = $subtotal2 * 0.11;
        $grandTotal2 = $subtotal2 + $ppn2;

        $otherPenawaran->update([
            'total_biaya' => $totalBiaya2,
            'total_margin' => $totalMargin2,
            'ppn' => $ppn2,
            'grand_total_with_ppn' => $grandTotal2,
        ]);

        echo "✓ Created History Penawaran: {$otherPenawaran->id} ({$otherPenawaran->no_penawaran}) for price trend testing\n";

        echo "\n" . str_repeat("=", 70) . "\n";
        echo "TEST DATA READY FOR API TESTING\n";
        echo str_repeat("=", 70) . "\n\n";

        echo "📊 Summary:\n";
        echo "  Client ID: {$client->id}\n";
        echo "  Source Penawaran ID: {$sourcePenawaran->id}\n";
        echo "  Target Penawaran ID: {$targetPenawaran->id}\n";
        echo "  History Penawaran ID: {$otherPenawaran->id}\n";
        echo "  Material IDs: " . implode(", ", array_values($materialIds)) . "\n\n";

        echo "🧪 Ready for API tests:\n";
        echo "  1. Copy items (Source Penawaran {$sourcePenawaran->id} → Target Penawaran {$targetPenawaran->id})\n";
        echo "  2. Get price trends (Material: " . implode(", ", array_values($materialIds)) . ")\n";
        echo "  3. Find similar penawaran (Client ID: {$client->id})\n";
    }
}
