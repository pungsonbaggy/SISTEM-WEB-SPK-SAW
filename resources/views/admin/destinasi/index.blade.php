@extends('layouts.app')

@section('judul', 'Data Destinasi – Admin')

@section('konten')
    <div class="admin-nav-bar">
        <div class="admin-nav-tabs">
            <a href="{{ route('admin.destinasi.index') }}" class="admin-tab-link active">
                <i class="bi bi-geo-alt"></i> Data Destinasi
            </a>
            <a href="{{ route('admin.bobot.index') }}" class="admin-tab-link">
                <i class="bi bi-sliders"></i> Bobot Segmen
            </a>
            <a href="{{ route('admin.aturan.index') }}" class="admin-tab-link">
                <i class="bi bi-funnel"></i> Aturan Kelayakan
            </a>
            <a href="{{ route('admin.analisis.index') }}" class="admin-tab-link">
                <i class="bi bi-bar-chart-line"></i> Analisis Sebaran
            </a>
        </div>
        <a href="{{ route('admin.destinasi.create') }}" class="btn-marine py-2 px-3" style="font-size: 0.86rem;">
            <i class="bi bi-plus-lg"></i> Tambah Destinasi
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Data Destinasi</h1>
            <p class="text-muted small mb-0">
                Daftar alternatif destinasi wisata bahari beserta nilai matriks keputusan (C1–C6).
            </p>
        </div>
        <span class="badge bg-light text-dark border font-mono px-2 py-1">{{ $destinasi->count() }} Destinasi Aktif</span>
    </div>

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-custom">
                <thead>
                <tr>
                    <th style="width: 75px;">Kode</th>
                    <th>Destinasi</th>
                    <th class="text-end" style="width: 110px;">C1 Jarak</th>
                    <th class="text-end" style="width: 120px;">C2 Biaya</th>
                    <th class="text-center" style="width: 85px;">C3 Fas.</th>
                    <th class="text-center" style="width: 85px;">C4 Indah</th>
                    <th class="text-center" style="width: 85px;">C5 Bersih</th>
                    <th class="text-center" style="width: 165px;">C6 (Snk/Div/Pnt/Plu)</th>
                    <th class="text-end" style="width: 140px;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($destinasi as $d)
                    @php $skor = $d->skorAktivitas->pluck('skor', 'minat'); @endphp
                    <tr>
                        <td>
                            <span class="badge bg-dark text-white font-mono">{{ $d->kode }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($d->foto)
                                    <img src="{{ $d->foto }}" alt="{{ $d->nama }}"
                                         class="rounded border d-none d-sm-block flex-shrink-0"
                                         style="width: 48px; height: 34px; object-fit: cover;">
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $d->nama }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $d->total_fasilitas }} fasilitas &bull; {{ $d->total_aktivitas }} aktivitas</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-end font-mono">{{ number_format($d->c1_jarak, 0, ',', '.') }} km</td>
                        <td class="text-end font-mono">Rp {{ number_format($d->c2_biaya, 0, ',', '.') }}rb</td>
                        <td class="text-center font-mono">{{ $d->c3_fasilitas }}</td>
                        <td class="text-center font-mono">{{ $d->c4_keindahan }}</td>
                        <td class="text-center font-mono">{{ $d->c5_kebersihan }}</td>
                        <td class="text-center font-mono text-muted">
                            {{ $skor['Snorkeling'] ?? '-' }}/{{ $skor['Diving'] ?? '-' }}/{{ $skor['Wisata Pantai'] ?? '-' }}/{{ $skor['Wisata Pulau'] ?? '-' }}
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.destinasi.edit', $d) }}"
                               class="btn btn-sm btn-outline-dark px-2 py-1" style="border-radius: 6px;">Ubah</a>
                            <form method="POST" action="{{ route('admin.destinasi.destroy', $d) }}"
                                   class="d-inline"
                                   onsubmit="return confirm('Hapus destinasi {{ $d->kode }}? Skor C6-nya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger px-2 py-1" style="border-radius: 6px;" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
