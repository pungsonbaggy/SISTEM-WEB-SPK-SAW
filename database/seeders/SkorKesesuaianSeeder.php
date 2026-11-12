<?php

namespace Database\Seeders;

use App\Models\Destinasi;
use App\Models\SkorKesesuaianAktivitas;
use Illuminate\Database\Seeder;

/**
 * Seeder matriks skor kesesuaian aktivitas (C6) per minat untuk 14 destinasi.
 */
class SkorKesesuaianSeeder extends Seeder
{
    public function run(): void
    {
        $matriks = [
            'A1' => ['Snorkeling' => 5, 'Diving' => 5, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
            'A2' => ['Snorkeling' => 5, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
            'A3' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A4' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A5' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A6' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A7' => ['Snorkeling' => 5, 'Diving' => 5, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
            'A8' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A9' => ['Snorkeling' => 5, 'Diving' => 5, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
            'A10' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A11' => ['Snorkeling' => 5, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A12' => ['Snorkeling' => 1, 'Diving' => 1, 'Wisata Pantai' => 5, 'Wisata Pulau' => 1],
            'A13' => ['Snorkeling' => 5, 'Diving' => 5, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
            'A14' => ['Snorkeling' => 5, 'Diving' => 5, 'Wisata Pantai' => 5, 'Wisata Pulau' => 5],
        ];

        foreach ($matriks as $kode => $skorMinat) {
            $destinasi = Destinasi::where('kode', $kode)->first();
            if (! $destinasi) {
                continue;
            }

            foreach ($skorMinat as $minat => $skor) {
                SkorKesesuaianAktivitas::updateOrCreate(
                    ['destinasi_id' => $destinasi->id, 'minat' => $minat],
                    ['skor' => $skor]
                );
            }
        }
    }
}
