<?php

namespace Database\Seeders;

use App\Models\AturanKelayakan;
use Illuminate\Database\Seeder;

/**
 * Aturan kelayakan per minat (Tabel 3.24 skripsi).
 * Destinasi dengan skor C6 < threshold dikeluarkan sebelum perangkingan.
 */
class AturanKelayakanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'minat' => 'Snorkeling', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 2,
                'keterangan' => 'Destinasi dengan skor C6 di bawah 2 tidak diikutkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Diving', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 2,
                'keterangan' => 'Destinasi dengan skor C6 di bawah 2 tidak diikutkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Wisata Pantai', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 2,
                'keterangan' => 'Destinasi dengan skor C6 di bawah 2 tidak diikutkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Wisata Pulau', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 2,
                'keterangan' => 'Destinasi dengan skor C6 di bawah 2 tidak diikutkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        foreach ($data as $d) {
            AturanKelayakan::updateOrCreate(['minat' => $d['minat']], $d);
        }
    }
}
