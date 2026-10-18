<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Services\SawService;

/**
 * Halaman beranda: form input profil wisatawan (F-01).
 *
 * Controller ini hanya menyiapkan daftar pilihan yang sah lalu
 * mengirimkannya ke view. Tidak ada perhitungan di sini.
 */
class BerandaController extends Controller
{
    public function index()
    {
        return view('beranda', [
            'minat' => SawService::MINAT,
            'anggaran' => SawService::ANGGARAN,
            'jarak' => SawService::JARAK,
            'kriteria' => SawService::KRITERIA,
            'jumlahDestinasi' => Destinasi::count(),
        ]);
    }
}
