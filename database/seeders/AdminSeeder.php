<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nama' => 'Admin Test',
                'telepon' => '081234567890',
                'password' => 'password',
                'role' => 'admin',
                'wajib_ganti_password' => false,
            ]
        );
    }
}