<?php

namespace Database\Seeders;

use App\Models\Destinasi;
use App\Models\SkorKesesuaianAktivitas;
use Illuminate\Database\Seeder;

/**
 * Skor C6 (kesesuaian aktivitas) untuk 15 destinasi x 4 minat (Tabel 3.13).
 * Inilah dasar penyaringan kelayakan pada SawService.
 */
class SkorKesesuaianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'A1', 'minat' => 'Snorkeling', 'skor' => 5],
            ['kode' => 'A1', 'minat' => 'Diving', 'skor' => 3],
            ['kode' => 'A1', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A1', 'minat' => 'Wisata Pulau', 'skor' => 4],
            ['kode' => 'A2', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A2', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A2', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A2', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A3', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A3', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A3', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A3', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A4', 'minat' => 'Snorkeling', 'skor' => 1],
            ['kode' => 'A4', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A4', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A4', 'minat' => 'Wisata Pulau', 'skor' => 1],
            ['kode' => 'A5', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A5', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A5', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A5', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A6', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A6', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A6', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A6', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A7', 'minat' => 'Snorkeling', 'skor' => 4],
            ['kode' => 'A7', 'minat' => 'Diving', 'skor' => 5],
            ['kode' => 'A7', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A7', 'minat' => 'Wisata Pulau', 'skor' => 5],
            ['kode' => 'A8', 'minat' => 'Snorkeling', 'skor' => 3],
            ['kode' => 'A8', 'minat' => 'Diving', 'skor' => 2],
            ['kode' => 'A8', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A8', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A9', 'minat' => 'Snorkeling', 'skor' => 4],
            ['kode' => 'A9', 'minat' => 'Diving', 'skor' => 3],
            ['kode' => 'A9', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A9', 'minat' => 'Wisata Pulau', 'skor' => 4],
            ['kode' => 'A10', 'minat' => 'Snorkeling', 'skor' => 1],
            ['kode' => 'A10', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A10', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A10', 'minat' => 'Wisata Pulau', 'skor' => 1],
            ['kode' => 'A11', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A11', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A11', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A11', 'minat' => 'Wisata Pulau', 'skor' => 1],
            ['kode' => 'A12', 'minat' => 'Snorkeling', 'skor' => 1],
            ['kode' => 'A12', 'minat' => 'Diving', 'skor' => 1],
            ['kode' => 'A12', 'minat' => 'Wisata Pantai', 'skor' => 4],
            ['kode' => 'A12', 'minat' => 'Wisata Pulau', 'skor' => 1],
            ['kode' => 'A13', 'minat' => 'Snorkeling', 'skor' => 4],
            ['kode' => 'A13', 'minat' => 'Diving', 'skor' => 3],
            ['kode' => 'A13', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A13', 'minat' => 'Wisata Pulau', 'skor' => 4],
            ['kode' => 'A14', 'minat' => 'Snorkeling', 'skor' => 2],
            ['kode' => 'A14', 'minat' => 'Diving', 'skor' => 2],
            ['kode' => 'A14', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A14', 'minat' => 'Wisata Pulau', 'skor' => 2],
            ['kode' => 'A15', 'minat' => 'Snorkeling', 'skor' => 5],
            ['kode' => 'A15', 'minat' => 'Diving', 'skor' => 4],
            ['kode' => 'A15', 'minat' => 'Wisata Pantai', 'skor' => 3],
            ['kode' => 'A15', 'minat' => 'Wisata Pulau', 'skor' => 5],
        ];

        $id = Destinasi::pluck('id', 'kode');

        foreach ($data as $d) {
            SkorKesesuaianAktivitas::updateOrCreate(
                ['destinasi_id' => $id[$d['kode']], 'minat' => $d['minat']],
                ['skor' => $d['skor']]
            );
        }
    }
}
