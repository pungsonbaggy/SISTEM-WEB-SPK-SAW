<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AturanKelayakan;
use App\Services\SawService;
use Illuminate\Http\Request;

class AturanController extends Controller
{
    public function index()
    {
        return view('admin.aturan.index', [
            'aturan' => AturanKelayakan::orderBy('id')->get(),
            'minat' => SawService::MINAT,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'threshold' => ['required', 'array'],
            'threshold.*' => ['required', 'integer', 'between:1,5'],
        ], [
            'threshold.*.between' => 'Ambang harus bernilai 1 sampai 5.',
        ]);

        foreach ($data['threshold'] as $minat => $nilai) {
            AturanKelayakan::updateOrCreate(
                ['minat' => $minat],
                [
                    'kriteria' => 'C6',
                    'operator' => '>=',
                    'threshold' => (int) $nilai,
                    'keterangan' => "Destinasi dengan skor C6 di bawah {$nilai} tidak diikutkan",
                ]
            );
        }

        return redirect()->route('admin.aturan.index')
            ->with('sukses', 'Aturan kelayakan berhasil diperbarui.');
    }
}
