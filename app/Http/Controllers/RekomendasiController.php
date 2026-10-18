<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\LogRekomendasi;
use App\Services\SawService;
use Illuminate\Support\Str;

/**
 * Menangani permintaan rekomendasi.
 *
 * Alur:
 *   POST /rekomendasi  -> validasi, hitung, simpan ke session + log, redirect
 *   GET  /rekomendasi  -> tampilkan hasil dari session
 *
 * Kenapa memakai redirect (bukan langsung return view)?
 * Supaya kalau halaman di-refresh, browser tidak mengirim ulang form
 * (menghindari data ganda pada log_rekomendasi).
 */
class RekomendasiController extends Controller
{
    public function store(ProfilRequest $request, SawService $saw)
    {
        $profil = $request->validated();
        $hasil = $saw->hitung($profil['minat'], $profil['anggaran'], $profil['jarak']);

        // Kalau tidak ada destinasi yang layak, jangan tampilkan halaman kosong.
        if (empty($hasil['hasil'])) {
            return back()
                ->withInput()
                ->with('galat', 'Tidak ada destinasi yang memenuhi syarat kelayakan untuk minat tersebut. '
                    . 'Silakan pilih minat lain atau hubungi admin.');
        }

        $top5 = array_slice($hasil['hasil'], 0, 5);
        $sesiId = (string) Str::uuid();

        // Catat ke log_rekomendasi untuk analisis sebaran pada Bab IV.
        foreach ($top5 as $i => $row) {
            LogRekomendasi::create([
                'sesi_id' => $sesiId,
                'minat' => $profil['minat'],
                'anggaran' => $profil['anggaran'],
                'jarak' => $profil['jarak'],
                'destinasi_id' => $row['destinasi_id'],
                'peringkat' => $i + 1,
                'nilai_v' => round($row['v'], 4),
            ]);
        }

        $request->session()->put('rekomendasi', [
            'sesi_id' => $sesiId,
            'profil' => $profil,
            'bobot' => $hasil['bobot'],
            'hasil' => $top5,
            'jumlah_layak' => count($hasil['hasil']),
            'dikeluarkan' => $hasil['dikeluarkan'],
            'ambang' => $hasil['ambang'],
        ]);

        return redirect()->route('rekomendasi.show');
    }

    public function show(SawService $saw)
    {
        $data = session('rekomendasi');

        if (! $data) {
            return redirect()->route('beranda')
                ->with('galat', 'Silakan isi profil terlebih dahulu.');
        }

        return view('rekomendasi', $data + ['kriteria' => SawService::KRITERIA]);
    }
}
