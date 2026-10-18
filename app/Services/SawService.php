<?php

namespace App\Services;

use App\Models\BobotSegmen;
use App\Models\Destinasi;
use App\Models\AturanKelayakan;

/**
 * ==========================================================================
 *  SawService — seluruh logika perhitungan Simple Additive Weighting (SAW)
 * ==========================================================================
 *
 * Kelas ini sengaja TIDAK berisi kode tampilan dan TIDAK memanggil view.
 * Tugasnya hanya: hitung. Karena itu ia bisa diuji terpisah lewat
 * `php artisan saw:verify` (lihat app/Console/Commands/VerifikasiSaw.php).
 *
 * Alur (sesuai Subbab 3.4.6 skripsi):
 *   1. ambil matriks keputusan (15 destinasi x 6 kriteria)
 *   2. ambil dan gabungkan bobot sesuai profil wisatawan
 *   3. normalisasi matriks dengan Rumus 2.1 (benefit) dan 2.2 (cost)
 *   4. saring destinasi yang tidak layak (skor C6 < ambang)
 *   5. hitung Vi dengan Rumus 2.3
 *   6. urutkan dari Vi terbesar
 *
 * CATATAN PENTING (jangan diubah tanpa memahami akibatnya):
 *   Normalisasi selalu dihitung dari SELURUH 15 destinasi, bukan hanya
 *   kandidat yang lolos penyaringan. Kalau dihitung dari kandidat saja,
 *   nilai r berubah setiap kali profil berubah dan hasil tidak bisa
 *   diverifikasi manual seperti pada Tabel 3.14 skripsi.
 */
class SawService
{
    /** Pilihan yang sah untuk form. Dipakai juga oleh validasi. */
    public const MINAT = ['Snorkeling', 'Diving', 'Wisata Pantai', 'Wisata Pulau'];
    public const ANGGARAN = ['Rendah', 'Sedang', 'Tinggi'];
    public const JARAK = ['Dekat', 'Sedang', 'Jauh'];

    /** Nama kriteria untuk ditampilkan. Urutan = kolom w1..w6. */
    public const KRITERIA = [
        'C1' => 'Jarak dari Pusat Kota',
        'C2' => 'Biaya Masuk',
        'C3' => 'Kelengkapan Fasilitas',
        'C4' => 'Keindahan Alam',
        'C5' => 'Kebersihan Pantai',
        'C6' => 'Kesesuaian Aktivitas',
    ];

    /**
     * Titik masuk utama.
     *
     * @return array{
     *   bobot: array<int,float>,
     *   hasil: array<int,array>,
     *   dikeluarkan: array<int,string>,
     *   ambang: int,
     *   normalisasi: array<string,array<int,float>>
     * }
     */
    public function hitung(string $minat, string $anggaran, string $jarak): array
    {
        // (1) Matriks keputusan: 15 destinasi beserta skor C6-nya.
        $destinasi = Destinasi::with('skorAktivitas')->orderBy('id')->get();

        // Skor C6 khusus minat yang dipilih, dipetakan per id destinasi.
        $skorC6 = [];
        foreach ($destinasi as $d) {
            $skorC6[$d->id] = (int) ($d->skorUntuk($minat) ?? 0);
        }

        // (2) Bobot sesuai profil (minat + anggaran + jarak).
        $bobot = $this->bobotProfil($minat, $anggaran, $jarak);

        // (3) Normalisasi seluruh 15 destinasi.
        $normalisasi = $this->normalisasi($destinasi, $skorC6);

        // (4) Ambang kelayakan untuk minat ini (default 2).
        $ambang = $this->ambang($minat);

        // (5) Hitung Vi hanya untuk destinasi yang layak.
        $hasil = [];
        $dikeluarkan = [];

        foreach ($destinasi as $d) {
            if ($skorC6[$d->id] < $ambang) {
                $dikeluarkan[] = $d->kode;
                continue;
            }

            $kontribusi = [];
            $v = 0.0;
            for ($i = 0; $i < 6; $i++) {
                // kontribusi kriteria ke-i = bobot x nilai ternormalisasi
                $kontribusi[$i] = $bobot[$i] * $normalisasi[$d->kode][$i];
                $v += $kontribusi[$i];
            }

            $hasil[] = [
                'kode' => $d->kode,
                'nama' => $d->nama,
                'destinasi_id' => $d->id,
                'skor_c6' => $skorC6[$d->id],
                'r' => $normalisasi[$d->kode],
                'kontribusi' => $kontribusi,
                'v' => $v,
                'v_tampil' => round($v, 4),
            ];
        }

        // (6) Urutkan menurun. Kalau Vi sama, urutkan berdasarkan kode
        //     supaya hasil selalu konsisten (tidak acak).
        usort($hasil, function ($a, $b) {
            return [$b['v'], $a['kode']] <=> [$a['v'], $b['kode']];
        });

        return [
            'bobot' => $bobot,
            'hasil' => $hasil,
            'dikeluarkan' => $dikeluarkan,
            'ambang' => $ambang,
            'normalisasi' => $normalisasi,
        ];
    }

    /**
     * Gabungkan bobot tiga segmen lalu normalisasi ulang.
     *
     * Rumus: wj = (w_minat_j + w_anggaran_j + w_jarak_j) / 3
     * Hasil akhirnya dipaksa berjumlah tepat 1.0000.
     */
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

            // Kalau data bobot belum di-seed, hentikan dengan pesan jelas
            // daripada menghasilkan angka 0 yang membingungkan.
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

    /**
     * Normalisasi bobot agar totalnya tepat 1.0000.
     *
     * Caranya: bagi dengan total, bulatkan 4 desimal, lalu koreksi elemen
     * terbesar dengan selisih pembulatan. Tanpa koreksi ini, penjumlahan
     * empat desimal bisa menghasilkan 0.9999 dan menggagalkan uji
     * "sigma w = 1.0000" pada Bab IV.
     */
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

    /**
     * Normalisasi matriks keputusan.
     *
     * C1, C2 = cost    -> r = min(x) / x        (Rumus 2.2)
     * C3..C5 = benefit -> r = x / max(x)        (Rumus 2.1)
     * C6     = benefit -> r = skor / max(skor)  (Rumus 2.1, per minat)
     *
     * min dan max diambil dari SELURUH destinasi (lihat catatan di atas).
     */
    public function normalisasi($destinasi, array $skorC6): array
    {
        $minC1 = $destinasi->min('c1_jarak');
        $minC2 = $destinasi->min('c2_biaya');
        $maxC3 = $destinasi->max('c3_fasilitas');
        $maxC4 = $destinasi->max('c4_keindahan');
        $maxC5 = $destinasi->max('c5_kebersihan');
        $maxC6 = max($skorC6 ?: [1]);

        $r = [];
        foreach ($destinasi as $d) {
            $r[$d->kode] = [
                $minC1 / (float) $d->c1_jarak,          // C1 cost
                $minC2 / (float) $d->c2_biaya,          // C2 cost
                (float) $d->c3_fasilitas / $maxC3,      // C3 benefit
                (float) $d->c4_keindahan / $maxC4,      // C4 benefit
                (float) $d->c5_kebersihan / $maxC5,     // C5 benefit
                $skorC6[$d->id] / $maxC6,               // C6 benefit (per minat)
            ];
        }

        return $r;
    }

    /** Ambang kelayakan untuk satu minat; default 2 bila aturan belum ada. */
    public function ambang(string $minat): int
    {
        return (int) (AturanKelayakan::where('minat', $minat)->value('threshold') ?? 2);
    }

    /**
     * Analisis sebaran rekomendasi untuk 36 kombinasi profil.
     * Dipakai halaman admin/analisis dan pembahasan Bab IV.
     */
    public function analisisSebaran(): array
    {
        $semua = Destinasi::orderBy('id')->get();
        $hitung = [];
        $juara = [];
        foreach ($semua as $d) {
            $hitung[$d->kode] = ['top5' => 0, 'juara' => 0, 'nama' => $d->nama, 'kunjungan' => $d->kunjungan_2025];
            $juara[$d->kode] = 0;
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
                        $juara[$hasil[0]['kode']]++;
                    }
                }
            }
        }

        $baris = [];
        foreach ($hitung as $kode => $d) {
            $baris[] = [
                'kode' => $kode,
                'nama' => $d['nama'],
                'kunjungan' => $d['kunjungan'],
                'top5' => $d['top5'],
                'persen' => $totalProfil ? round($d['top5'] / $totalProfil * 100, 1) : 0,
                'juara' => $juara[$kode],
            ];
        }

        usort($baris, fn ($a, $b) => $b['top5'] <=> $a['top5']);

        return ['total_profil' => $totalProfil, 'baris' => $baris];
    }
}
