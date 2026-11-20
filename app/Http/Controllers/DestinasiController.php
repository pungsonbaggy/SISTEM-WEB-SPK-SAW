<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Services\SawService;
use Illuminate\Http\Request;

class DestinasiController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('rekomendasi');
        $destinasi = Destinasi::orderBy('id')->get();

        return view('destinasi.index', [
            'destinasi' => $destinasi,
            'totalDestinasi' => $destinasi->count(),
        ]);
    }

    public function show(string $kode, SawService $saw)
    {
        $destinasi = Destinasi::with('skorAktivitas')->where('kode', $kode)->firstOrFail();
        $sesi = session('rekomendasi');

        $kontribusi = null;
        $nilaiV = null;
        $bobot = null;
        $normalisasi = null;

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
            'nilaiV' => $nilaiV,
            'bobot' => $bobot,
            'normalisasi' => $normalisasi,
            'sesi' => $sesi,
        ]);
    }
}
