@extends('layouts.app')

@section('judul', 'Hasil Rekomendasi — SPK Wisata Bahari')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
        <div>
            <h1 class="h3 mb-1">Rekomendasi untukmu</h1>
            <p class="text-muted mb-0">
                Lima destinasi teratas dari {{ $jumlah_layak }} destinasi yang memenuhi syarat kelayakan.
            </p>
        </div>
        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary">Ulangi rekomendasi</a>
    </div>

    {{-- Ringkasan profil --}}
    <div class="card mb-4">
        <div class="card-body">
            <span class="text-muted small me-2">Profil:</span>
            <span class="badge bg-primary me-1">{{ $profil['minat'] }}</span>
            <span class="badge bg-primary me-1">Anggaran {{ $profil['anggaran'] }}</span>
            <span class="badge bg-primary">{{ $profil['jarak'] }}</span>

            @if (count($dikeluarkan) > 0)
                <div class="alert alert-light border mt-3 mb-0 py-2 small">
                    <strong>{{ count($dikeluarkan) }} destinasi tidak ditampilkan</strong>
                    ({{ implode(', ', $dikeluarkan) }}) karena skor kesesuaian aktivitasnya
                    di bawah ambang {{ $ambang }} untuk minat {{ $profil['minat'] }}.
                    Destinasi tersebut tetap ada di sistem, hanya tidak layak untuk minat ini.
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel hasil --}}
    <div class="card mb-4">
        <div class="card-header">Peringkat destinasi</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width:60px">Rank</th>
                    <th>Destinasi</th>
                    <th class="text-center">Skor C6</th>
                    <th>Faktor penentu utama</th>
                    <th class="text-end" style="width:100px">Nilai Vi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($hasil as $i => $row)
                    @php
                        // Ambil tiga kriteria dengan kontribusi terbesar
                        $kontribusi = $row['kontribusi'];
                        arsort($kontribusi);
                        $utama = array_slice($kontribusi, 0, 3, true);
                    @endphp
                    <tr>
                        <td><span class="badge badge-peringkat">{{ $i + 1 }}</span></td>
                        <td>
                            <strong>{{ $row['nama'] }}</strong>
                            <div class="text-muted small">{{ $row['kode'] }}</div>
                        </td>
                        <td class="text-center">{{ $row['skor_c6'] }}</td>
                        <td>
                            @foreach ($utama as $idx => $nilai)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ $kriteria['C' . ($idx + 1)] }}
                                </span>
                            @endforeach
                        </td>
                        <td class="text-end nilai-v">{{ number_format($row['v'], 4, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('destinasi.show', $row['kode']) }}"
                               class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bobot yang dipakai --}}
    <div class="card">
        <div class="card-header">
            <a class="text-decoration-none" data-bs-toggle="collapse" href="#panelBobot">
                Bobot kriteria yang digunakan (klik untuk melihat)
            </a>
        </div>
        <div class="collapse" id="panelBobot">
            <div class="card-body">
                <table class="table table-sm mb-2">
                    <thead>
                    <tr>
                        @foreach ($kriteria as $kode => $nama)
                            <th class="text-center">{{ $kode }}<br><span class="fw-normal small text-muted">{{ $nama }}</span></th>
                        @endforeach
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach ($bobot as $w)
                            <td class="text-center">{{ number_format($w, 4, ',', '.') }}</td>
                        @endforeach
                        <td class="text-center fw-bold">{{ number_format(array_sum($bobot), 4, ',', '.') }}</td>
                    </tr>
                    </tbody>
                </table>
                <p class="text-muted small mb-0">
                    Bobot dihitung dari rata-rata tiga segmen profil
                    ({{ $profil['minat'] }}, {{ $profil['anggaran'] }}, {{ $profil['jarak'] }}),
                    lalu dinormalkan sehingga totalnya 1,0000.
                </p>
            </div>
        </div>
    </div>
@endsection
