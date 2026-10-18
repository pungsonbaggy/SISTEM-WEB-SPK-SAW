<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SawService;

/**
 * Analisis sebaran rekomendasi untuk 36 kombinasi profil.
 * Halaman ini menghasilkan tabel yang dipakai pada pembahasan Bab IV
 * mengenai potensi pemerataan kunjungan.
 */
class AnalisisController extends Controller
{
    public function index(SawService $saw)
    {
        $analisis = $saw->analisisSebaran();

        return view('admin.analisis', [
            'baris' => $analisis['baris'],
            'totalProfil' => $analisis['total_profil'],
        ]);
    }
}
