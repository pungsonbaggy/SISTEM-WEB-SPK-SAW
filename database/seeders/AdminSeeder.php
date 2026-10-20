<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Akun admin untuk halaman pengelolaan.
 * GANTI PASSWORD sebelum aplikasi dipakai di luar komputer sendiri.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@spk.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
            ]
        );
    }
}
