<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * CRUD data destinasi beserta skor C6 per minat (F-11).
 * Ini adalah sumber data matriks keputusan pada Tabel 3.12 dan 3.13.
 */
class DestinasiController extends Controller
{
    public function index()
    {
        return view('admin.destinasi.index', [
            'destinasi' => Destinasi::with('skorAktivitas')->orderBy('id')->get(),
            'minat' => SawService::MINAT,
        ]);
    }

    public function create()
    {
        return view('admin.destinasi.form', [
            'destinasi' => new Destinasi(['kode' => 'A' . (Destinasi::count() + 1)]),
            'minat' => SawService::MINAT,
            'skor' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $skor = $request->input('skor', []);

        DB::transaction(function () use ($data, $skor) {
            $destinasi = Destinasi::create($data);
            $this->simpanSkor($destinasi, $skor);
        });

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', 'Destinasi baru berhasil ditambahkan.');
    }

    public function edit(Destinasi $destinasi)
    {
        $destinasi->load('skorAktivitas');

        return view('admin.destinasi.form', [
            'destinasi' => $destinasi,
            'minat' => SawService::MINAT,
            'skor' => $destinasi->skorAktivitas->pluck('skor', 'minat')->all(),
        ]);
    }

    public function update(Request $request, Destinasi $destinasi)
    {
        $data = $this->validasi($request, $destinasi);

        DB::transaction(function () use ($data, $request, $destinasi) {
            $destinasi->update($data);
            $this->simpanSkor($destinasi, $request->input('skor', []));
        });

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', "Destinasi {$destinasi->kode} berhasil diperbarui.");
    }

    public function destroy(Destinasi $destinasi)
    {
        $destinasi->delete(); // skor C6 ikut terhapus (cascade)

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', "Destinasi {$destinasi->kode} berhasil dihapus.");
    }

    /** Aturan validasi dipakai bersama oleh store() dan update(). */
    private function validasi(Request $request, ?Destinasi $destinasi = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:5', Rule::unique('destinasi', 'kode')->ignore($destinasi?->id)],
            'nama' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'c1_jarak' => ['required', 'numeric', 'min:0.1', 'max:999'],
            'c2_biaya' => ['required', 'numeric', 'min:0', 'max:99999'],
            'c3_fasilitas' => ['required', 'integer', 'between:1,5'],
            'c4_keindahan' => ['required', 'integer', 'between:1,5'],
            'c5_kebersihan' => ['required', 'integer', 'between:1,5'],
            'kunjungan_2025' => ['required', 'integer', 'min:0'],
            'skor' => ['required', 'array'],
            'skor.*' => ['required', 'integer', 'between:1,5'],
        ], [
            'kode.unique' => 'Kode destinasi sudah dipakai.',
            'skor.*.between' => 'Skor kesesuaian aktivitas harus 1 sampai 5.',
        ]);
    }

    /** Simpan empat skor C6 (satu per minat) untuk satu destinasi. */
    private function simpanSkor(Destinasi $destinasi, array $skor): void
    {
        foreach (SawService::MINAT as $minat) {
            if (! isset($skor[$minat])) {
                continue;
            }

            $destinasi->skorAktivitas()->updateOrCreate(
                ['minat' => $minat],
                ['skor' => (int) $skor[$minat]]
            );
        }
    }
}
