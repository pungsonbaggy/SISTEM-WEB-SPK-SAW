@extends('layouts.app')

@section('judul', $destinasi->nama . ' — SPK Wisata Bahari')

@section('konten')
    <a href="{{ route('beranda') }}" class="text-decoration-none small">&larr; Kembali ke beranda</a>

    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h1 class="h3 mb-1">{{ $destinasi->nama }}</h1>
                    <p class="text-muted mb-2">Kode {{ $destinasi->kode }}</p>
                </div>
                @if ($nilaiV !== null)
                    <div class="text-end">
                        <div class="text-muted small">Nilai preferensi (Vi)</div>
                        <div class="h3 mb-0 nilai-v text-primary">{{ number_format($nilaiV, 4, ',', '.') }}</div>
                    </div>
                @endif
            </div>
            <p class="mb-0">{{ $destinasi->deskripsi }}</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Skor kriteria --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Skor kriteria</div>
                <table class="table mb-0">
                    <tbody>
                    <tr>
                        <td>C1 Jarak dari pusat kota</td>
                        <td class="text-end">{{ number_format($destinasi->c1_jarak, 2, ',', '.') }} km</td>
                    </tr>
                    <tr>
                        <td>C2 Biaya masuk</td>
                        <td class="text-end">Rp{{ number_format($destinasi->c2_biaya, 0, ',', '.') }} ribu</td>
                    </tr>
                    <tr>
                        <td>C3 Kelengkapan fasilitas</td>
                        <td class="text-end">{{ $destinasi->c3_fasilitas }} / 5</td>
                    </tr>
                    <tr>
                        <td>C4 Keindahan alam</td>
                        <td class="text-end">{{ $destinasi->c4_keindahan }} / 5</td>
                    </tr>
                    <tr>
                        <td>C5 Kebersihan pantai</td>
                        <td class="text-end">{{ $destinasi->c5_kebersihan }} / 5</td>
                    </tr>
                    <tr>
                        <td>C6 Kesesuaian aktivitas</td>
                        <td class="text-end">
                            @foreach ($destinasi->skorAktivitas as $s)
                                <span class="badge bg-light text-dark border me-1">
                                    {{ $s->minat }}: {{ $s->skor }}
                                </span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>Kunjungan 2025</td>
                        <td class="text-end">{{ number_format($destinasi->kunjungan_2025, 0, ',', '.') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rincian perhitungan --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Rincian perhitungan SAW</div>
                <div class="card-body">
                    @if ($kontribusi !== null)
                        <p class="text-muted small">
                            Untuk profil <strong>{{ $sesi['profil']['minat'] }}</strong> +
                            {{ $sesi['profil']['anggaran'] }} + {{ $sesi['profil']['jarak'] }}.
                        </p>
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th class="text-end">Bobot</th>
                                <th class="text-end">r</th>
                                <th class="text-end">w &times; r</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($kontribusi as $i => $k)
                                <tr>
                                    <td>C{{ $i + 1 }}</td>
                                    <td class="text-end">{{ number_format($bobot[$i], 4, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($normalisasi[$i], 4, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($k, 4, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Total Vi</td>
                                <td class="text-end">{{ number_format($nilaiV, 4, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                        <p class="text-muted small mb-0">
                            Nilai Vi adalah jumlah kolom terakhir. Inilah angka yang diurutkan
                            sistem untuk menentukan peringkat.
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            Buka halaman ini dari hasil rekomendasi untuk melihat rincian
                            perhitungan sesuai profilmu.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
