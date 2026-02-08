<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profil;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Admin Profil
        Profil::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'nama_depan' => 'Admin',
                'nama_belakang' => 'User',
                'telepon' => '081234567890',
            ]
        );

        // Create Manager User
        $manager = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
            ]
        );

        // Create Manager Profil
        Profil::updateOrCreate(
            ['user_id' => $manager->id],
            [
                'nama_depan' => 'Manager',
                'nama_belakang' => 'User',
                'telepon' => '081234567891',
            ]
        );
    }
}
