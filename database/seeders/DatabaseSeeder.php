<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder utama untuk menginisialisasi data destinasi, skor C6, bobot segmen, aturan, dan akun admin.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DestinasiSeeder::class,
            SkorKesesuaianSeeder::class,
            BobotSegmenSeeder::class,
            AturanKelayakanSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
