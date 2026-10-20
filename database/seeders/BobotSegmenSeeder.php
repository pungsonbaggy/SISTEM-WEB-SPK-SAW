<?php

namespace Database\Seeders;

use App\Models\BobotSegmen;
use Illuminate\Database\Seeder;

/**
 * Bobot kriteria per segmen profil (Tabel 3.10 skripsi).
 * Total setiap baris tepat 1.0000.
 */
class BobotSegmenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'segmen' => 'minat', 'nilai' => 'Snorkeling',
                'w1' => 0.1397, 'w2' => 0.1446, 'w3' => 0.1520,
                'w4' => 0.1961, 'w5' => 0.1838, 'w6' => 0.1838,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Diving',
                'w1' => 0.0952, 'w2' => 0.1282, 'w3' => 0.1868,
                'w4' => 0.2125, 'w5' => 0.1832, 'w6' => 0.1941,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Wisata Pantai',
                'w1' => 0.1959, 'w2' => 0.2010, 'w3' => 0.1527,
                'w4' => 0.1527, 'w5' => 0.1654, 'w6' => 0.1323,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Wisata Pulau',
                'w1' => 0.1654, 'w2' => 0.1614, 'w3' => 0.1417,
                'w4' => 0.1968, 'w5' => 0.1654, 'w6' => 0.1693,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'anggaran', 'nilai' => 'Rendah',
                'w1' => 0.1917, 'w2' => 0.1986, 'w3' => 0.1316,
                'w4' => 0.1732, 'w5' => 0.1617, 'w6' => 0.1432,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'anggaran', 'nilai' => 'Sedang',
                'w1' => 0.1481, 'w2' => 0.1609, 'w3' => 0.1609,
                'w4' => 0.1846, 'w5' => 0.1718, 'w6' => 0.1737,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'anggaran', 'nilai' => 'Tinggi',
                'w1' => 0.1092, 'w2' => 0.1149, 'w3' => 0.1839,
                'w4' => 0.2069, 'w5' => 0.1954, 'w6' => 0.1897,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'jarak', 'nilai' => 'Dekat',
                'w1' => 0.2000, 'w2' => 0.1729, 'w3' => 0.1458,
                'w4' => 0.1604, 'w5' => 0.1646, 'w6' => 0.1563,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'jarak', 'nilai' => 'Sedang',
                'w1' => 0.1349, 'w2' => 0.1567, 'w3' => 0.1627,
                'w4' => 0.2005, 'w5' => 0.1746, 'w6' => 0.1706,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'jarak', 'nilai' => 'Jauh',
                'w1' => 0.1105, 'w2' => 0.1512, 'w3' => 0.1657,
                'w4' => 0.2034, 'w5' => 0.1890, 'w6' => 0.1802,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        foreach ($data as $d) {
            BobotSegmen::updateOrCreate(
                ['segmen' => $d['segmen'], 'nilai' => $d['nilai']],
                $d
            );
        }
    }
}
