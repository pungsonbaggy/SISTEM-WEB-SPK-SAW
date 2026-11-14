<?php

namespace App\Services;

use App\Models\AturanKelayakan;
use App\Models\BobotSegmen;
use App\Models\Destinasi;

class SawService
{
    public const MINAT = ['Snorkeling', 'Diving', 'Wisata Pantai', 'Wisata Pulau'];
    public const ANGGARAN = ['Rendah', 'Sedang', 'Tinggi'];
    public const JARAK = ['Dekat', 'Sedang', 'Jauh'];

    public const KRITERIA = [
        'C1' => 'Jarak dari Pusat Kota',
        'C2' => 'Biaya',
        'C3' => 'Kelengkapan Fasilitas',
        'C4' => 'Keindahan Alam',
        'C5' => 'Kebersihan Pantai',
        'C6' => 'Keragaman Kesesuaian Aktivitas',
    ];

    // Tahap utama perhitungan SAW: gabungkan bobot, normalisasi matriks, saring ambang C6, dan urutkan Vi
    public function hitung(string $minat, string $anggaran, string $jarak): array
    {
        $destinasi = Destinasi::with('skorAktivitas')->orderBy('id')->get();
        $bobot = $this->bobotProfil($minat, $anggaran, $jarak);
        $normalisasi = $this->normalisasi($destinasi, $minat);
        $ambang = $this->ambang($minat);

        $hasil = [];
        $dikeluarkan = [];

        foreach ($destinasi as $d) {
            $skorC6 = (int) ($d->skorAktivitas->firstWhere('minat', $minat)?->skor ?? $d->c6_aktivitas ?? 1);

            if ($skorC6 < $ambang) {
                $dikeluarkan[] = $d->kode;
                continue;
            }

            $kontribusi = [];
            $v = 0.0;
            for ($i = 0; $i < 6; $i++) {
                $kontribusi[$i] = $bobot[$i] * $normalisasi[$d->kode][$i];
                $v += $kontribusi[$i];
            }

            $hasil[] = [
                'kode' => $d->kode,
                'nama' => $d->nama,
                'destinasi_id' => $d->id,
                'skor_c6' => $skorC6,
                'r' => $normalisasi[$d->kode],
                'kontribusi' => $kontribusi,
                'v' => $v,
                'v_tampil' => round($v, 4),
                'foto' => $d->foto,
                'deskripsi' => $d->deskripsi,
                'c1_jarak' => $d->c1_jarak,
                'c2_biaya' => $d->c2_biaya,
                'c3_fasilitas' => $d->c3_fasilitas,
                'c4_keindahan' => $d->c4_keindahan,
                'c5_kebersihan' => $d->c5_kebersihan,
                'total_fasilitas' => $d->total_fasilitas,
                'fasilitas_list' => $d->fasilitas_list,
                'aktivitas_list' => $d->aktivitas_list,
            ];
        }

        usort($hasil, fn ($a, $b) => [$b['v'], $a['kode']] <=> [$a['v'], $b['kode']]);

        return [
            'bobot' => $bobot,
            'hasil' => $hasil,
            'dikeluarkan' => $dikeluarkan,
            'ambang' => $ambang,
            'normalisasi' => $normalisasi,
        ];
    }

    // Rata-rata bobot dari 3 segmen profil (minat, anggaran, jarak) lalu normalkan agar total = 1
    public function bobotProfil(string $minat, string $anggaran, string $jarak): array
    {
        $sumber = [
            ['minat', $minat],
            ['anggaran', $anggaran],
            ['jarak', $jarak],
        ];

        $vektor = [];
        foreach ($sumber as [$segmen, $nilai]) {
            $baris = BobotSegmen::where('segmen', $segmen)
                ->where('nilai', $nilai)
                ->first();

            if (! $baris) {
                throw new \RuntimeException(
                    "Bobot segmen '{$segmen}' = '{$nilai}' tidak ditemukan. Jalankan: php artisan db:seed"
                );
            }

            $vektor[] = $baris->sebagaiArray();
        }

        $gabung = [];
        for ($i = 0; $i < 6; $i++) {
            $gabung[$i] = ($vektor[0][$i] + $vektor[1][$i] + $vektor[2][$i]) / 3;
        }

        return $this->normalisasiBobot($gabung);
    }

    public function normalisasiBobot(array $bobot): array
    {
        $total = array_sum($bobot);
        if ($total <= 0) {
            throw new \RuntimeException('Total bobot tidak boleh nol.');
        }

        $hasil = [];
        foreach ($bobot as $b) {
            $hasil[] = round($b / $total, 4);
        }

        $selisih = round(1.0 - array_sum($hasil), 4);
        if (abs($selisih) > 0) {
            $iMax = array_keys($hasil, max($hasil))[0];
            $hasil[$iMax] = round($hasil[$iMax] + $selisih, 4);
        }

        return $hasil;
    }

    // Normalisasi matriks keputusan: Cost (C1, C2) = min/x, Benefit (C3-C6) = x/max
    public function normalisasi($destinasi, ?string $minat = null): array
    {
        $minC1 = (float) $destinasi->min('c1_jarak');
        $minC2 = (float) $destinasi->min('c2_biaya');
        $maxC3 = (float) $destinasi->max('c3_fasilitas');
        $maxC4 = (float) $destinasi->max('c4_keindahan');
        $maxC5 = (float) $destinasi->max('c5_kebersihan');
        $maxC6 = 5.0;

        $r = [];
        foreach ($destinasi as $d) {
            if ($minat !== null) {
                $skorC6 = (float) ($d->skorAktivitas->firstWhere('minat', $minat)?->skor ?? $d->c6_aktivitas ?? 1);
            } else {
                $skorC6 = (float) ($d->c6_aktivitas ?? 5);
            }

            $r[$d->kode] = [
                $minC1 / (float) $d->c1_jarak,
                $minC2 / (float) $d->c2_biaya,
                (float) $d->c3_fasilitas / $maxC3,
                (float) $d->c4_keindahan / $maxC4,
                (float) $d->c5_kebersihan / $maxC5,
                $skorC6 / $maxC6,
            ];
        }

        return $r;
    }

    public function ambang(string $minat): int
    {
        return (int) (AturanKelayakan::where('minat', $minat)->value('threshold') ?? 1);
    }

    public function analisisSebaran(): array
    {
        $semua = Destinasi::orderBy('id')->get();
        $hitung = [];
        foreach ($semua as $d) {
            $hitung[$d->kode] = ['top5' => 0, 'juara' => 0, 'nama' => $d->nama];
        }

        $totalProfil = 0;
        foreach (self::MINAT as $m) {
            foreach (self::ANGGARAN as $a) {
                foreach (self::JARAK as $j) {
                    $totalProfil++;
                    $hasil = $this->hitung($m, $a, $j)['hasil'];
                    foreach (array_slice($hasil, 0, 5) as $row) {
                        $hitung[$row['kode']]['top5']++;
                    }
                    if (! empty($hasil)) {
                        $hitung[$hasil[0]['kode']]['juara']++;
                    }
                }
            }
        }

        $baris = [];
        foreach ($hitung as $kode => $d) {
            $baris[] = [
                'kode' => $kode,
                'nama' => $d['nama'],
                'top5' => $d['top5'],
                'persen' => $totalProfil ? round($d['top5'] / $totalProfil * 100, 1) : 0,
                'juara' => $d['juara'],
            ];
        }

        usort($baris, fn ($a, $b) => $b['top5'] <=> $a['top5']);

        return ['total_profil' => $totalProfil, 'baris' => $baris];
    }
}
