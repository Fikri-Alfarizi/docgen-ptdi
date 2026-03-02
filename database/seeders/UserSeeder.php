<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@ptdi.co.id'],
            [
                'name' => 'Administrator PT DI',
                'password' => Hash::make('password123'),
                'nik' => '0000000000000000',
                'role' => 'admin',
            ]
        );

        // Akun Karyawan/User Biasa
        User::updateOrCreate(
            ['email' => 'budi@karyawan.ptdi'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'nik' => '3201234567890001',
                'role' => 'user',
            ]
        );
    }
}
