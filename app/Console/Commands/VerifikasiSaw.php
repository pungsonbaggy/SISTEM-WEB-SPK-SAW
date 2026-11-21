<?php

namespace App\Console\Commands;

use App\Services\SawService;
use Illuminate\Console\Command;

/**
 * Perintah CLI untuk memverifikasi hasil perhitungan SAW terhadap data acuan (tests/golden_tests.json).
 */
class VerifikasiSaw extends Command
{
    protected $signature = 'saw:verify {--toleransi=0.001 : Batas selisih yang masih dianggap sama}';

    protected $description = 'Memverifikasi perhitungan SAW terhadap angka acuan skripsi';

    public function handle(SawService $saw): int
    {
        $path = base_path('tests/golden_tests.json');
        if (! file_exists($path)) {
            $this->error("Berkas acuan tidak ditemukan: {$path}");
            return self::FAILURE;
        }

        $golden = json_decode(file_get_contents($path), true);
        $tol = (float) $this->option('toleransi');
        $gagal = 0;
        $total = 0;

        $this->newLine();
        $this->info('VERIFIKASI PERHITUNGAN SAW');
        $this->line(str_repeat('=', 78));

        foreach ($golden['kasus'] as $kasus) {
            $p = $kasus['profil'];
            $this->newLine();
            $this->comment("Profil: {$p['minat']} + {$p['anggaran']} + {$p['jarak']}");

            $hasil = $saw->hitung($p['minat'], $p['anggaran'], $p['jarak']);

            $total++;
            $selisihBobot = 0.0;
            foreach ($kasus['bobot'] as $i => $acuan) {
                $selisihBobot = max($selisihBobot, abs($hasil['bobot'][$i] - $acuan));
            }
            $gagal += $this->lapor('Bobot segmen sesuai acuan', $selisihBobot <= $tol, $selisihBobot);

            $total++;
            $sum = abs(array_sum($hasil['bobot']) - 1.0);
            $gagal += $this->lapor('Total bobot = 1,0000', $sum <= 1e-9, $sum);

            $total++;
            $sama = $hasil['dikeluarkan'] == $kasus['dikeluarkan'];
            $this->lapor(
                'Penyaringan kelayakan (' . count($hasil['dikeluarkan']) . ' destinasi keluar)',
                $sama,
                0,
                'keluar: ' . (implode(', ', $hasil['dikeluarkan']) ?: '-')
            );
            $gagal += $sama ? 0 : 1;

            $this->line('    Lima besar:');
            foreach ($kasus['top5'] as $i => $acuan) {
                $total++;
                $baris = $hasil['hasil'][$i] ?? null;
                $cocokKode = $baris && $baris['kode'] === $acuan['kode'];
                $selisih = $baris ? abs($baris['v'] - $acuan['v']) : 9;
                $ok = $cocokKode && $selisih <= $tol;
                $gagal += $ok ? 0 : 1;
                $this->line(sprintf(
                    '      %d. %s %-24s Vi=%s %s',
                    $i + 1,
                    $baris['kode'] ?? '??',
                    $baris['nama'] ?? '-',
                    number_format($baris['v'] ?? 0, 4, ',', '.'),
                    $ok ? '<fg=green>OK</>' : '<fg=red>GAGAL</>'
                ));
            }
        }

        $this->newLine();
        $this->comment('Pemeriksaan seluruh 36 kombinasi profil');
        $this->line(str_repeat('-', 78));
        $melenceng = 0;
        $jumlah = 0;
        foreach (SawService::MINAT as $m) {
            foreach (SawService::ANGGARAN as $a) {
                foreach (SawService::JARAK as $j) {
                    $jumlah++;
                    $w = $saw->bobotProfil($m, $a, $j);
                    if (abs(array_sum($w) - 1.0) > 1e-9) {
                        $melenceng++;
                    }
                }
            }
        }
        $total++;
        $gagal += $this->lapor("Total bobot 36 kombinasi ({$jumlah} kombinasi)", $melenceng === 0, $melenceng);

        $this->newLine();
        $this->line(str_repeat('=', 78));
        $lulus = $total - $gagal;
        if ($gagal === 0) {
            $this->info("HASIL: SEMUA LULUS ({$lulus}/{$total} pemeriksaan)");
            $this->line('Selisih maksimum yang diizinkan: ' . $tol);
            return self::SUCCESS;
        }

        $this->error("HASIL: {$gagal} dari {$total} pemeriksaan GAGAL");
        return self::FAILURE;
    }

    private function lapor(string $label, bool $ok, float $selisih = 0, string $catatan = ''): int
    {
        $status = $ok ? '<fg=green>LULUS</>' : '<fg=red>GAGAL</>';
        $ekstra = $catatan !== '' ? " [{$catatan}]" : ($selisih > 0 ? ' selisih=' . round($selisih, 6) : '');
        $this->line("    {$status}  {$label}{$ekstra}");

        return $ok ? 0 : 1;
    }
}
