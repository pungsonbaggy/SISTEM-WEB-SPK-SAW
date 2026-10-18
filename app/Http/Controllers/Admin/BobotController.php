<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BobotSegmen;
use App\Services\SawService;
use Illuminate\Http\Request;

/**
 * Pengelolaan bobot per segmen profil (F-12).
 * Bobot yang disimpan harus berjumlah 1.0000; sistem menormalkan ulang
 * setelah admin mengubah angka, supaya tidak pernah ada baris yang
 * totalnya 0,9999.
 */
class BobotController extends Controller
{
    public function index()
    {
        $semua = BobotSegmen::orderBy('id')->get()->groupBy('segmen');

        return view('admin.bobot.index', [
            'semua' => $semua,
            'kriteria' => SawService::KRITERIA,
        ]);
    }

    public function update(Request $request, SawService $saw)
    {
        $data = $request->validate([
            'bobot' => ['required', 'array'],
            'bobot.*' => ['required', 'array', 'size:6'],
            'bobot.*.*' => ['required', 'numeric', 'min:0', 'max:1'],
        ], [
            'bobot.*.size' => 'Setiap segmen harus memiliki enam bobot.',
        ]);

        foreach ($data['bobot'] as $id => $nilai) {
            $baris = BobotSegmen::find($id);
            if (! $baris) {
                continue;
            }

            // Normalkan supaya totalnya tepat 1.0000.
            $w = $saw->normalisasiBobot(array_map('floatval', array_values($nilai)));

            $baris->update([
                'w1' => $w[0], 'w2' => $w[1], 'w3' => $w[2],
                'w4' => $w[3], 'w5' => $w[4], 'w6' => $w[5],
            ]);
        }

        return redirect()->route('admin.bobot.index')
            ->with('sukses', 'Bobot segmen diperbarui dan dinormalkan ke total 1,0000.');
    }
}
