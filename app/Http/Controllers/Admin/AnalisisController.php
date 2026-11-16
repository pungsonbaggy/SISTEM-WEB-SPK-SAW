<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SawService;

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
