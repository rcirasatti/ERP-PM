<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Material;
use App\Models\Penawaran;
use App\Models\ItemPenawaran;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HistoricalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test client
        $client = Client::firstOrCreate(
            ['nama' => 'PT. Maju Jaya'],
            ['kontak' => 'Budi Santoso', 'email' => 'budi@majujaya.com', 'telepon' => '081234567890', 'alamat' => 'Jakarta']
        );

        // Get or create admin user for pengeluaran
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin System',
                'email' => 'admin@erp.test',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        // Get or create test materials
        $materials = [];
        $materialData = [
            ['kode' => 'MAT001', 'nama' => 'Batu Bata', 'satuan' => 'pcs', 'harga' => 2000, 'type' => 'BARANG'],
            ['kode' => 'MAT002', 'nama' => 'Semen', 'satuan' => 'kg', 'harga' => 1500, 'type' => 'BARANG'],
            ['kode' => 'MAT003', 'nama' => 'Pasir', 'satuan' => 'kg', 'harga' => 500, 'type' => 'BARANG'],
            ['kode' => 'JAR001', 'nama' => 'Jasa Tukang', 'satuan' => 'hari', 'harga' => 350000, 'type' => 'JASA'],
        ];

        foreach ($materialData as $data) {
            $materials[] = Material::firstOrCreate(
                ['kode' => $data['kode']],
                array_merge($data, ['supplier_id' => null])
            );
        }

        // ===== PROJECT 1: Proyek Rumah Tinggal (Jan 2025) =====
        // Penawaran: Rp 100,000,000
        // Pengeluaran Actual: Rp 105,000,000 (5% overrun)
        $penawaran1 = Penawaran::create([
            'no_penawaran' => 'PW-2025-01-001',
            'client_id' => $client->id,
            'tanggal' => Carbon::parse('2025-01-10'),
            'status' => 'disetujui',
            'ai_status' => 'approved',
            'total_biaya' => 90000000,
            'total_margin' => 10000000,
            'ppn' => 11000000,
            'grand_total_with_ppn' => 111000000,
            'ai_prediksi_lr' => 105000000,
            'ai_prediksi_ma' => 104500000,
            'margin_status' => 'overrun',
            'ai_notes' => 'Prediksi overrun 5% berdasarkan data historis proyek serupa'
        ]);

        // Items untuk penawaran 1
        ItemPenawaran::create([
            'penawaran_id' => $penawaran1->id,
            'material_id' => $materials[0]->id, // Batu Bata
            'jumlah' => 50000,
            'harga_asli' => 2000,
            'persentase_margin' => 10,
            'harga_jual' => 2200,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran1->id,
            'material_id' => $materials[1]->id, // Semen
            'jumlah' => 20000,
            'harga_asli' => 1500,
            'persentase_margin' => 10,
            'harga_jual' => 1650,
        ]);

        // Proyek
        $proyek1 = Proyek::create([
            'penawaran_id' => $penawaran1->id,
            'client_id' => $client->id,
            'nama' => 'Proyek Rumah Tinggal - PT. Maju Jaya',
            'deskripsi' => 'Pembangunan rumah tinggal 2 lantai',
            'lokasi' => 'Jakarta Selatan',
            'tanggal_mulai' => Carbon::parse('2025-01-15'),
            'tanggal_selesai' => Carbon::parse('2025-03-15'),
            'status' => 'selesai',
            'persentase_progres' => 100,
        ]);

        // Pengeluaran actual (overrun)
        $pengeluaranData1 = [
            ['kategori' => 'material', 'deskripsi' => 'Batu Bata - pembelian pusat', 'jumlah' => 55000 * 2200],
            ['kategori' => 'material', 'deskripsi' => 'Semen - pembelian pusat', 'jumlah' => 22000 * 1650],
            ['kategori' => 'material', 'deskripsi' => 'Pasir putih', 'jumlah' => 5000000],
            ['kategori' => 'gaji', 'deskripsi' => 'Upah tenaga kerja', 'jumlah' => 15000000],
            ['kategori' => 'lainnya', 'deskripsi' => 'Biaya administrasi & transportasi', 'jumlah' => 2500000],
        ];

        foreach ($pengeluaranData1 as $data) {
            Pengeluaran::create(array_merge([
                'proyek_id' => $proyek1->id,
                'tanggal' => Carbon::parse('2025-02-15'),
                'dibuat_oleh' => $admin->id,
            ], $data));
        }

        // ===== PROJECT 2: Proyek Gedung Kantor (Mar 2025) =====
        // Penawaran: Rp 250,000,000
        // Pengeluaran Actual: Rp 248,000,000 (aman - lebih rendah dari penawaran)
        $penawaran2 = Penawaran::create([
            'no_penawaran' => 'PW-2025-03-001',
            'client_id' => $client->id,
            'tanggal' => Carbon::parse('2025-03-05'),
            'status' => 'disetujui',
            'ai_status' => 'approved',
            'total_biaya' => 220000000,
            'total_margin' => 30000000,
            'ppn' => 27500000,
            'grand_total_with_ppn' => 277500000,
            'ai_prediksi_lr' => 248000000,
            'ai_prediksi_ma' => 250000000,
            'margin_status' => 'aman',
            'ai_notes' => 'Prediksi aman. Proyek ini termasuk kategori standard spec.'
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran2->id,
            'material_id' => $materials[1]->id, // Semen
            'jumlah' => 50000,
            'harga_asli' => 1500,
            'persentase_margin' => 12,
            'harga_jual' => 1680,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran2->id,
            'material_id' => $materials[3]->id, // Jasa Tukang
            'jumlah' => 120,
            'harga_asli' => 350000,
            'persentase_margin' => 15,
            'harga_jual' => 402500,
        ]);

        $proyek2 = Proyek::create([
            'penawaran_id' => $penawaran2->id,
            'client_id' => $client->id,
            'nama' => 'Proyek Gedung Kantor - PT. Maju Jaya',
            'deskripsi' => 'Renovasi gedung kantor 5 lantai',
            'lokasi' => 'Jakarta Pusat',
            'tanggal_mulai' => Carbon::parse('2025-03-10'),
            'tanggal_selesai' => Carbon::parse('2025-06-10'),
            'status' => 'selesai',
            'persentase_progres' => 100,
        ]);

        // Pengeluaran actual (aman)
        $pengeluaranData2 = [
            ['kategori' => 'material', 'deskripsi' => 'Semen - batch 1', 'jumlah' => 30000 * 1680],
            ['kategori' => 'material', 'deskripsi' => 'Semen - batch 2', 'jumlah' => 20000 * 1680],
            ['kategori' => 'gaji', 'deskripsi' => 'Upah tukang - periode 1', 'jumlah' => 40 * 402500],
            ['kategori' => 'gaji', 'deskripsi' => 'Upah tukang - periode 2', 'jumlah' => 80 * 402500],
            ['kategori' => 'lainnya', 'deskripsi' => 'Biaya overhead & misc', 'jumlah' => 3500000],
        ];

        foreach ($pengeluaranData2 as $data) {
            Pengeluaran::create(array_merge([
                'proyek_id' => $proyek2->id,
                'tanggal' => Carbon::parse('2025-05-15'),
                'dibuat_oleh' => $admin->id,
            ], $data));
        }

        // ===== PROJECT 3: Proyek Kolam Renang (Jun 2025) =====
        // Penawaran: Rp 180,000,000
        // Pengeluaran Actual: Rp 189,000,000 (7% overrun - significant!)
        $penawaran3 = Penawaran::create([
            'no_penawaran' => 'PW-2025-06-001',
            'client_id' => $client->id,
            'tanggal' => Carbon::parse('2025-06-01'),
            'status' => 'disetujui',
            'ai_status' => 'approved',
            'total_biaya' => 160000000,
            'total_margin' => 20000000,
            'ppn' => 19800000,
            'grand_total_with_ppn' => 199800000,
            'ai_prediksi_lr' => 189000000,
            'ai_prediksi_ma' => 187000000,
            'margin_status' => 'overrun',
            'ai_notes' => 'PERINGATAN: Proyek kolam renang sering mengalami overrun 6-8% akibat variabilitas tanah.'
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran3->id,
            'material_id' => $materials[2]->id, // Pasir
            'jumlah' => 100000,
            'harga_asli' => 500,
            'persentase_margin' => 8,
            'harga_jual' => 540,
        ]);

        $proyek3 = Proyek::create([
            'penawaran_id' => $penawaran3->id,
            'client_id' => $client->id,
            'nama' => 'Proyek Kolam Renang - PT. Maju Jaya',
            'deskripsi' => 'Konstruksi kolam renang Olympic-size',
            'lokasi' => 'Tangerang',
            'tanggal_mulai' => Carbon::parse('2025-06-15'),
            'tanggal_selesai' => Carbon::parse('2025-09-15'),
            'status' => 'selesai',
            'persentase_progres' => 100,
        ]);

        // Pengeluaran actual (overrun)
        $pengeluaranData3 = [
            ['kategori' => 'material', 'deskripsi' => 'Pasir khusus - supply 1', 'jumlah' => 60000 * 540],
            ['kategori' => 'material', 'deskripsi' => 'Pasir khusus - supply 2 (extra)', 'jumlah' => 50000 * 540],
            ['kategori' => 'material', 'deskripsi' => 'Material waterproofing', 'jumlah' => 8000000],
            ['kategori' => 'gaji', 'deskripsi' => 'Upah kerja konstruksi', 'jumlah' => 45000000],
            ['kategori' => 'lainnya', 'deskripsi' => 'Equipment rental & misc overrun', 'jumlah' => 5500000],
        ];

        foreach ($pengeluaranData3 as $data) {
            Pengeluaran::create(array_merge([
                'proyek_id' => $proyek3->id,
                'tanggal' => Carbon::parse('2025-08-15'),
                'dibuat_oleh' => $admin->id,
            ], $data));
        }

        $this->command->info('✓ Historical data seeded successfully!');
        $this->command->info('  - 3 historical projects created');
        $this->command->info('  - 9 expense records for ML training');
        $this->command->info('  - Project 1: 5% overrun | Project 2: Safe | Project 3: 7% overrun');
    }
}
