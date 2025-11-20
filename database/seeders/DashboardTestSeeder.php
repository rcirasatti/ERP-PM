<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pengeluaran;
use App\Models\Tugas;
use App\Models\Client;

class DashboardTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing client
        $client = Client::first() ?? Client::create([
            'nama' => 'Test Client',
            'email' => 'test@client.com',
            'nomor_hp' => '08123456789',
            'alamat' => 'Jakarta',
            'status' => 'aktif',
        ]);

        // Create multiple penawarans
        $penawaran1 = Penawaran::create([
            'no_penawaran' => 'PW-' . time() . '-001',
            'client_id' => $client->id,
            'tanggal' => now(),
            'status' => 'draft',
            'total_margin' => 2000000,
            'total_biaya' => 8000000,
            'ppn' => 1440000,
            'grand_total_with_ppn' => 9440000,
        ]);

        $penawaran2 = Penawaran::create([
            'no_penawaran' => 'PW-' . time() . '-002',
            'client_id' => $client->id,
            'tanggal' => now(),
            'status' => 'disetujui',
            'total_margin' => 3000000,
            'total_biaya' => 10000000,
            'ppn' => 1800000,
            'grand_total_with_ppn' => 11800000,
        ]);

        // Create multiple projects with different statuses
        $proyek1 = Proyek::create([
            'nama' => 'Instalasi Panel Surya A',
            'deskripsi' => 'Instalasi panel surya untuk rumah',
            'client_id' => $client->id,
            'penawaran_id' => $penawaran2->id,
            'lokasi' => 'Serpong',
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(20),
            'status' => 'instalasi',
            'persentase_progres' => 65.0,
        ]);

        $proyek2 = Proyek::create([
            'nama' => 'Survei Lokasi B',
            'deskripsi' => 'Survei lokasi untuk proyek baru',
            'client_id' => $client->id,
            'penawaran_id' => $penawaran2->id,
            'lokasi' => 'BSD',
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->addDays(30),
            'status' => 'survei',
            'persentase_progres' => 30.0,
        ]);

        $proyek3 = Proyek::create([
            'nama' => 'Proyek Pengujian C',
            'deskripsi' => 'Pengujian sistem',
            'client_id' => $client->id,
            'penawaran_id' => $penawaran2->id,
            'lokasi' => 'Tangerang',
            'tanggal_mulai' => now()->subDays(20),
            'tanggal_selesai' => now()->addDays(10),
            'status' => 'pengujian',
            'persentase_progres' => 80.0,
        ]);

        $proyek4 = Proyek::create([
            'nama' => 'Proyek Selesai D',
            'deskripsi' => 'Proyek yang sudah selesai',
            'client_id' => $client->id,
            'penawaran_id' => $penawaran2->id,
            'lokasi' => 'Bandung',
            'tanggal_mulai' => now()->subDays(60),
            'tanggal_selesai' => now()->subDays(5),
            'status' => 'selesai',
            'persentase_progres' => 100.0,
        ]);

        // Create tasks for projects
        Tugas::create([
            'proyek_id' => $proyek1->id,
            'nama' => 'Persiapan Material',
            'selesai' => true,
        ]);

        Tugas::create([
            'proyek_id' => $proyek1->id,
            'nama' => 'Instalasi Struktur',
            'selesai' => false,
        ]);

        Tugas::create([
            'proyek_id' => $proyek2->id,
            'nama' => 'Survei Lapangan',
            'selesai' => false,
        ]);

        Tugas::create([
            'proyek_id' => $proyek3->id,
            'nama' => 'Testing Sistem',
            'selesai' => false,
        ]);

        // Create expenses
        Pengeluaran::create([
            'proyek_id' => $proyek1->id,
            'tanggal' => now()->subDays(5),
            'kategori' => 'material',
            'deskripsi' => 'Pembelian Panel Surya',
            'jumlah' => 3000000,
            'dibuat_oleh' => 1,
        ]);

        Pengeluaran::create([
            'proyek_id' => $proyek1->id,
            'tanggal' => now()->subDays(3),
            'kategori' => 'gaji',
            'deskripsi' => 'Bayar Tenaga Kerja',
            'jumlah' => 2000000,
            'dibuat_oleh' => 1,
        ]);

        Pengeluaran::create([
            'proyek_id' => $proyek2->id,
            'tanggal' => now()->subDays(1),
            'kategori' => 'lainnya',
            'deskripsi' => 'Biaya Transportasi Survei',
            'jumlah' => 500000,
            'dibuat_oleh' => 1,
        ]);

        Pengeluaran::create([
            'proyek_id' => $proyek3->id,
            'tanggal' => now(),
            'kategori' => 'material',
            'deskripsi' => 'Peralatan Testing',
            'jumlah' => 1500000,
            'dibuat_oleh' => 1,
        ]);
    }
}
