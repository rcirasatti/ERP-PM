<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'nama' => 'PT Mitra Konstruksi Utama',
                'kontak' => 'Hari Subagyo',
                'email' => 'hari@mitrakonst.co.id',
                'telepon' => '0811-1111-1111',
                'alamat' => 'Jl. Utama No. 5, Jakarta Selatan',
            ],
            [
                'nama' => 'CV Graha Permata',
                'kontak' => 'Dwi Retno',
                'email' => 'dwi@grahapermata.com',
                'telepon' => '0812-2222-2222',
                'alamat' => 'Jl. Permata No. 12, Depok',
            ],
            [
                'nama' => 'PT Sarana Industri Maju',
                'kontak' => 'Hendra Kusuma',
                'email' => 'hendra@saranaindustri.net',
                'telepon' => '0813-3333-3333',
                'alamat' => 'Jl. Industri No. 67, Bekasi',
            ],
            [
                'nama' => 'Perusahaan Distribusi Tangguh',
                'kontak' => 'Slamet Riyanto',
                'email' => 'slamet@distribusitangguh.id',
                'telepon' => '0814-4444-4444',
                'alamat' => 'Jl. Distribusi No. 34, Tangerang',
            ],
            [
                'nama' => 'PT Energi Hijau Indonesia',
                'kontak' => 'Lina Wijiastuti',
                'email' => 'lina@energihijau.co.id',
                'telepon' => '0815-5555-5555',
                'alamat' => 'Jl. Energi No. 78, Bogor',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
