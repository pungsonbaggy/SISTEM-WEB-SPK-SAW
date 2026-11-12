<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder akun administrator awal sistem.
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
