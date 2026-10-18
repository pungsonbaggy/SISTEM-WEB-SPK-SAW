<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Services\SawService;

/**
 * Halaman detail destinasi (F-08 dan F-09): skor enam kriteria
 * beserta kontribusinya terhadap nilai preferensi.
 */
class DestinasiController extends Controller
{
    public function show(string $kode, SawService $saw)
    {
        $destinasi = Destinasi::with('skorAktivitas')->where('kode', $kode)->firstOrFail();
        $sesi = session('rekomendasi');

        $kontribusi = null;
        $nilaiV = null;
        $bobot = null;
        $normalisasi = null;

        // Kalau wisatawan datang dari halaman hasil, tampilkan juga
        // rincian perhitungan untuk profil yang sedang aktif.
        if ($sesi) {
            $hasil = $saw->hitung($sesi['profil']['minat'], $sesi['profil']['anggaran'], $sesi['profil']['jarak']);
            $baris = collect($hasil['hasil'])->firstWhere('kode', $kode);

            if ($baris) {
                $kontribusi = $baris['kontribusi'];
                $nilaiV = $baris['v'];
                $normalisasi = $baris['r'];
            }
            $bobot = $hasil['bobot'];
        }

        return view('destinasi.show', [
            'destinasi' => $destinasi,
            'kriteria' => SawService::KRITERIA,
            'kontribusi' => $kontribusi,
            'normalisasi' => $normalisasi,
            'nilaiV' => $nilaiV,
            'bobot' => $bobot,
            'sesi' => $sesi,
        ]);
    }
}
