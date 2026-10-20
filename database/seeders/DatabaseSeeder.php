<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Urutan seeding penting:
 * destinasi dulu (karena skor C6 merujuk id destinasi),
 * baru skor, bobot, aturan, dan akun admin.
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
