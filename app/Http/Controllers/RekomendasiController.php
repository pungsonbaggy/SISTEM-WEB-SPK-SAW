<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\LogRekomendasi;
use App\Services\SawService;
use Illuminate\Support\Str;

class RekomendasiController extends Controller
{
    // Proses input profil wisatawan, hitung rekomendasi SAW, catat 5 besar ke log, dan simpan ke session
    public function store(ProfilRequest $request, SawService $saw)
    {
        $profil = $request->validated();
        $hasil = $saw->hitung($profil['minat'], $profil['anggaran'], $profil['jarak']);

        if (empty($hasil['hasil'])) {
            return back()
                ->withInput()
                ->with('galat', 'Tidak ada destinasi yang memenuhi syarat kelayakan untuk minat tersebut. '
                    . 'Silakan pilih minat lain atau hubungi admin.');
        }

        $top5 = array_slice($hasil['hasil'], 0, 5);
        $sesiId = (string) Str::uuid();

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
            'hasil' => $hasil['hasil'],
            'jumlah_layak' => count($hasil['hasil']),
            'dikeluarkan' => $hasil['dikeluarkan'],
            'ambang' => $hasil['ambang'],
        ]);

        return redirect()->route('rekomendasi.show');
    }

    public function show()
    {
        $data = session('rekomendasi');

        if (! $data) {
            return redirect()->route('beranda')
                ->with('galat', 'Silakan isi profil terlebih dahulu.');
        }

        return view('rekomendasi', $data + ['kriteria' => SawService::KRITERIA]);
    }
}
