<?php

namespace Database\Seeders;

use App\Models\AturanKelayakan;
use Illuminate\Database\Seeder;

/**
 * Seeder aturan ambang batas kelayakan awal (threshold = 1) untuk setiap minat aktivitas.
 */
class AturanKelayakanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'minat' => 'Snorkeling', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 1,
                'keterangan' => 'Seluruh destinasi dievaluasi dan diperingkatkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Diving', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 1,
                'keterangan' => 'Seluruh destinasi dievaluasi dan diperingkatkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Wisata Pantai', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 1,
                'keterangan' => 'Seluruh destinasi dievaluasi dan diperingkatkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'minat' => 'Wisata Pulau', 'kriteria' => 'C6', 'operator' => '>=', 'threshold' => 1,
                'keterangan' => 'Seluruh destinasi dievaluasi dan diperingkatkan',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        foreach ($data as $d) {
            AturanKelayakan::updateOrCreate(['minat' => $d['minat']], $d);
        }
    }
}
