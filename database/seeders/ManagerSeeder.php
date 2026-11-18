<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'manager@test.com'],
            [
                'nama' => 'Manager Test',
                'telepon' => '081234567890',
                'password' => 'password',
                'role' => 'manajer',
                'wajib_ganti_password' => false,
            ]
        );
    }
}
