<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Services\SawService;

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
