<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Buat akun User biasa untuk testing
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'User Mahasiswa',
                'email'    => 'user@example.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
            ]
        );

        $this->command->info('✅ Admin dan User seed berhasil dibuat!');
        $this->command->info('   Admin  → admin@example.com / password123');
        $this->command->info('   User   → user@example.com  / password123');
    }
}
