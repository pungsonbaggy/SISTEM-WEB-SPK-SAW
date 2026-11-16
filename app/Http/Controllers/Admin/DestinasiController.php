<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DestinasiController extends Controller
{
    private const FASILITAS = [
        'Area Parkir', 'Tempat Istirahat', 'Toilet Umum', 'Tempat Makan & Minum',
        'Tempat Ibadah', 'Area Foto', 'Wifi', 'Wahana/Rekreasi',
        'Area Bermain Anak', 'Penginapan/Homestay', 'Toko/Warung', 'Pos Keamanan',
    ];

    public function index()
    {
        return view('admin.destinasi.index', [
            'destinasi' => Destinasi::with('skorAktivitas')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.destinasi.form', [
            'destinasi' => new Destinasi(['kode' => 'A' . (Destinasi::count() + 1)]),
        ]);
    }

    public function store(Request $request)
    {
        $data = array_merge($this->validasi($request), $this->hitungFasilitasDanAktivitas($request));
        $data = array_merge($data, $this->simpanFotoLokal($request, $data['kode']));

        DB::transaction(function () use ($data, $request) {
            $destinasi = Destinasi::create($data);
            $this->simpanSkor($destinasi, $this->skorPerMinat($request));
        });

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', "Destinasi {$data['kode']} berhasil ditambahkan.");
    }

    public function edit(Destinasi $destinasi)
    {
        return view('admin.destinasi.form', [
            'destinasi' => $destinasi,
        ]);
    }

    public function update(Request $request, Destinasi $destinasi)
    {
        $data = array_merge($this->validasi($request, $destinasi), $this->hitungFasilitasDanAktivitas($request));
        $data = array_merge($data, $this->simpanFotoLokal($request, $data['kode'], $destinasi));

        DB::transaction(function () use ($data, $request, $destinasi) {
            $destinasi->update($data);
            $this->simpanSkor($destinasi, $this->skorPerMinat($request));
        });

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', "Destinasi {$destinasi->kode} ({$destinasi->nama}) berhasil diperbarui.");
    }

    public function destroy(Destinasi $destinasi)
    {
        $kode = $destinasi->kode;
        $destinasi->delete();

        return redirect()->route('admin.destinasi.index')
            ->with('sukses', "Destinasi {$kode} berhasil dihapus.");
    }

    // Simpan file foto utama dan galeri yang diunggah dari perangkat lokal ke folder public/images/destinasi
    private function simpanFotoLokal(Request $request, string $kode, ?Destinasi $destinasi = null): array
    {
        $dir = public_path('images/destinasi');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $prefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $kode));
        $fotoUtama = $destinasi?->foto;
        $fotoList = is_array($destinasi?->foto_list) ? $destinasi->foto_list : [];

        if ($request->hasFile('foto_file') && $request->file('foto_file')->isValid()) {
            $file = $request->file('foto_file');
            $namaFile = $prefix . '-utama-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $namaFile);
            $fotoUtama = '/images/destinasi/' . $namaFile;
        }

        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $i => $file) {
                if ($file && $file->isValid()) {
                    $namaFile = $prefix . '-' . ($i + 1) . '-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $namaFile);
                    $fotoList[$i] = '/images/destinasi/' . $namaFile;
                }
            }
        }

        $fotoList = array_values(array_filter($fotoList));

        if (empty($fotoUtama) && ! empty($fotoList)) {
            $fotoUtama = $fotoList[0];
        }
        if (empty($fotoList) && ! empty($fotoUtama)) {
            $fotoList = [$fotoUtama];
        }

        return [
            'foto' => $fotoUtama,
            'foto_list' => ! empty($fotoList) ? $fotoList : null,
        ];
    }

    // Konversi otomatis jumlah ceklis fasilitas menjadi skor C3 (1-5) dan aktivitas menjadi skor C6
    private function hitungFasilitasDanAktivitas(Request $request): array
    {
        $fasilitas = array_values(array_intersect($request->input('fasilitas_list', []), self::FASILITAS));
        $totalFasilitas = count($fasilitas);
        $c3 = match (true) {
            $totalFasilitas >= 11 => 5,
            $totalFasilitas >= 8 => 4,
            $totalFasilitas >= 5 => 3,
            $totalFasilitas >= 3 => 2,
            default => 1,
        };

        $aktivitasDipilih = array_values(array_intersect($request->input('aktivitas_list', []), SawService::MINAT));
        $totalAktivitas = count($aktivitasDipilih);
        $c6 = max(1, $totalAktivitas > 0 ? 1 + $totalAktivitas : 1);

        return [
            'fasilitas_list' => $fasilitas,
            'total_fasilitas' => $totalFasilitas,
            'c3_fasilitas' => $c3,
            'aktivitas_list' => $aktivitasDipilih,
            'total_aktivitas' => $totalAktivitas,
            'c6_aktivitas' => $c6,
        ];
    }

    private function skorPerMinat(Request $request): array
    {
        $aktivitasDipilih = (array) $request->input('aktivitas_list', []);
        $skor = [];

        foreach (SawService::MINAT as $minat) {
            $skor[$minat] = in_array($minat, $aktivitasDipilih) ? 5 : 1;
        }

        return $skor;
    }

    private function validasi(Request $request, ?Destinasi $destinasi = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:5', Rule::unique('destinasi', 'kode')->ignore($destinasi?->id)],
            'nama' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'c1_jarak' => ['required', 'numeric', 'min:0.1', 'max:999'],
            'c2_biaya' => ['required', 'numeric', 'min:0', 'max:99999'],
            'c4_keindahan' => ['required', 'integer', 'between:1,5'],
            'c5_kebersihan' => ['required', 'integer', 'between:1,5'],
            'fasilitas_list' => ['nullable', 'array'],
            'fasilitas_list.*' => ['string'],
            'aktivitas_list' => ['nullable', 'array'],
            'aktivitas_list.*' => ['string'],
            'foto_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'foto_galeri' => ['nullable', 'array'],
            'foto_galeri.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'kode.required' => 'Kode destinasi wajib diisi.',
            'kode.unique' => 'Kode destinasi sudah dipakai oleh destinasi lain.',
            'nama.required' => 'Nama destinasi wajib diisi.',
            'c1_jarak.required' => 'Kriteria jarak (C1) wajib diisi.',
            'c2_biaya.required' => 'Kriteria biaya (C2) wajib diisi.',
            'c4_keindahan.required' => 'Kriteria keindahan (C4) wajib dipilih.',
            'c5_kebersihan.required' => 'Kriteria kebersihan (C5) wajib dipilih.',
            'foto_file.image' => 'File foto utama harus berupa gambar (JPG, PNG, WEBP).',
            'foto_file.max' => 'Ukuran foto utama maksimal 5 MB.',
            'foto_galeri.*.image' => 'File galeri harus berupa gambar (JPG, PNG, WEBP).',
            'foto_galeri.*.max' => 'Ukuran setiap foto galeri maksimal 5 MB.',
        ]);
    }

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
