<?php

namespace Database\Seeders;

use App\Models\BobotSegmen;
use Illuminate\Database\Seeder;

/**
 * Bobot kriteria per segmen profil wisatawan.
 * Dihitung langsung dari data mentah 60 responden valid (Data_60_Responden.xlsx).
 */
class BobotSegmenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Segmen Minat Aktivitas (n = 60)
            [
                'segmen' => 'minat', 'nilai' => 'Snorkeling',
                'w1' => 0.11764705882352941,
                'w2' => 0.108359133126935,
                'w3' => 0.195046439628483,
                'w4' => 0.19195046439628485,
                'w5' => 0.19195046439628485,
                'w6' => 0.195046439628483,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Diving',
                'w1' => 0.10810810810810813,
                'w2' => 0.1114864864864865,
                'w3' => 0.1891891891891892,
                'w4' => 0.19594594594594597,
                'w5' => 0.19594594594594597,
                'w6' => 0.19932432432432434,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Wisata Pantai',
                'w1' => 0.15458937198067632,
                'w2' => 0.16666666666666666,
                'w3' => 0.19082125603864736,
                'w4' => 0.15217391304347827,
                'w5' => 0.19323671497584544,
                'w6' => 0.142512077294686,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'minat', 'nilai' => 'Wisata Pulau',
                'w1' => 0.16467065868263472,
                'w2' => 0.16467065868263472,
                'w3' => 0.16467065868263472,
                'w4' => 0.15868263473053892,
                'w5' => 0.17065868263473055,
                'w6' => 0.17664670658682635,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // Segmen Anggaran Perjalanan
            [
                'segmen' => 'anggaran', 'nilai' => 'Rendah',
                'w1' => 0.17125382262996941,
                'w2' => 0.16819571865443425,
                'w3' => 0.1620795107033639,
                'w4' => 0.14678899082568808,
                'w5' => 0.18042813455657492,
                'w6' => 0.17125382262996941,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'anggaran', 'nilai' => 'Sedang',
                'w1' => 0.14258911819887429,
                'w2' => 0.14634146341463414,
                'w3' => 0.18574108818011253,
                'w4' => 0.17260787992495308,
                'w5' => 0.18761726078799248,
                'w6' => 0.16510318949343339,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'anggaran', 'nilai' => 'Tinggi',
                'w1' => 0.11242603550295859,
                'w2' => 0.11637080867850098,
                'w3' => 0.19921104536489154,
                'w4' => 0.1893491124260355,
                'w5' => 0.1932938856015779,
                'w6' => 0.1893491124260355,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // Segmen Preferensi Jarak
            [
                'segmen' => 'jarak', 'nilai' => 'Dekat',
                'w1' => 0.16944444444444445,
                'w2' => 0.16388888888888889,
                'w3' => 0.16388888888888889,
                'w4' => 0.15000000000000000,
                'w5' => 0.18055555555555555,
                'w6' => 0.17222222222222222,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'jarak', 'nilai' => 'Sedang',
                'w1' => 0.13108614232209737,
                'w2' => 0.14232209737827714,
                'w3' => 0.19475655430711608,
                'w4' => 0.17228464419475656,
                'w5' => 0.18913857677902621,
                'w6' => 0.17041198501872659,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'segmen' => 'jarak', 'nilai' => 'Jauh',
                'w1' => 0.1226215644820296,
                'w2' => 0.12050739957716702,
                'w3' => 0.19027484143763215,
                'w4' => 0.19027484143763215,
                'w5' => 0.19238900634249473,
                'w6' => 0.1839323467230444,
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
