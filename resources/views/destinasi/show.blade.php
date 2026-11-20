@extends('layouts.app')

@section('judul', $destinasi->nama . ' – SPK Wisata Bahari Pesawaran')

@push('gaya')
<style>
    .detail-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #0d3b46 100%);
        color: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: 0 8px 24px -12px rgba(10, 46, 54, 0.25);
    }
    .detail-carousel,
    .detail-carousel .carousel-inner,
    .detail-carousel .carousel-item {
        height: 100%;
        min-height: 250px;
    }
    .detail-hero-img {
        width: 100%;
        height: 100%;
        min-height: 250px;
        max-height: 290px;
        object-fit: cover;
        display: block;
    }
    .carousel-photo-badge {
        position: absolute;
        bottom: 12px;
        left: 12px;
        z-index: 3;
        background: rgba(10, 46, 54, 0.82);
        color: #f8fafc;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
    }
    .detail-carousel .carousel-control-prev,
    .detail-carousel .carousel-control-next {
        width: 38px;
        height: 38px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(10, 46, 54, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        opacity: 0.9;
    }
    .detail-carousel .carousel-control-prev { left: 10px; }
    .detail-carousel .carousel-control-next { right: 10px; }
    .detail-carousel .carousel-control-prev:hover,
    .detail-carousel .carousel-control-next:hover {
        background: var(--primary);
        opacity: 1;
    }
    .detail-carousel .carousel-control-prev-icon,
    .detail-carousel .carousel-control-next-icon {
        width: 1.1rem;
        height: 1.1rem;
    }
    .vi-score-card {
        background: rgba(255, 255, 255, 0.97);
        color: var(--ink);
        border-radius: 12px;
        padding: 1.1rem 1.4rem;
        min-width: 185px;
        text-align: center;
        border: 1px solid rgba(153, 246, 228, 0.6);
        box-shadow: 0 6px 18px -6px rgba(0, 0, 0, 0.25);
    }
    .vi-score-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    .vi-score-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1.15;
    }
    .vi-score-sub {
        display: inline-block;
        margin-top: 0.45rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #0f766e;
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        padding: 0.18rem 0.6rem;
        border-radius: 6px;
    }
</style>
@endpush

@section('konten')
    @php
        $isPulau = str_starts_with(strtolower(trim($destinasi->nama)), 'pulau');
        $labelC2 = $isPulau ? 'Biaya & Penyeberangan' : 'Biaya';
        $semuaFoto = array_values(array_unique(array_filter(array_merge(
            [$destinasi->foto],
            $destinasi->foto_list ?? []
        ))));
        if (empty($semuaFoto)) {
            $semuaFoto = ['/images/destinasi/a1-1.jpg'];
        }
    @endphp

    <div class="mb-3">
        @if ($kontribusi !== null)
            <a href="{{ route('rekomendasi.show') }}" class="btn btn-outline-dark btn-sm px-3" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Hasil Rekomendasi
            </a>
        @else
            <a href="{{ route('destinasi.index') }}" class="btn btn-outline-dark btn-sm px-3" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Destinasi
            </a>
        @endif
    </div>

    <div class="detail-hero mb-4">
        <div class="row g-0 align-items-stretch">
            <div class="col-md-5 col-lg-5 position-relative">
                @if (count($semuaFoto) > 1)
                    <div id="galeriDestinasi" class="carousel slide detail-carousel" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach ($semuaFoto as $idx => $fotoUrl)
                                <div class="carousel-item {{ $idx === 0 ? 'active' : '' }} position-relative">
                                    <img src="{{ $fotoUrl }}" alt="{{ $destinasi->nama }} - Foto {{ $idx + 1 }}" class="detail-hero-img">
                                    <span class="carousel-photo-badge">
                                        <i class="bi bi-images me-1"></i>Foto {{ $idx + 1 }} / {{ count($semuaFoto) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galeriDestinasi" data-bs-slide="prev" title="Foto Sebelumnya">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galeriDestinasi" data-bs-slide="next" title="Foto Berikutnya">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Berikutnya</span>
                        </button>
                    </div>
                @else
                    <img src="{{ $semuaFoto[0] }}" alt="{{ $destinasi->nama }}" class="detail-hero-img">
                @endif
            </div>
            <div class="col-md-7 col-lg-7">
                <div class="p-4 p-lg-4 h-100 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-info text-dark px-2 py-1 fw-bold font-mono">{{ $destinasi->kode }}</span>
                            <span class="badge bg-white bg-opacity-10 text-light border border-secondary">Destinasi Wisata Bahari Pesawaran</span>
                        </div>
                        <h1 class="h3 fw-bold mb-2 text-white" style="font-family: 'Poppins', sans-serif;">{{ $destinasi->nama }}</h1>
                        <p class="mb-0 text-light opacity-75 small" style="line-height: 1.6;">
                            {{ $destinasi->deskripsi ?: 'Destinasi wisata bahari unggulan di Kabupaten Pesawaran, Lampung.' }}
                        </p>
                    </div>
                    @if ($nilaiV !== null)
                        <div class="vi-score-card flex-shrink-0">
                            <div class="vi-score-label">Nilai Preferensi (Vi)</div>
                            <div class="vi-score-value">{{ number_format($nilaiV, 4, ',', '.') }}</div>
                            <span class="vi-score-sub"><i class="bi bi-calculator me-1"></i>Hasil SAW</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-custom mb-4">
                <div class="card-header card-header-custom d-flex align-items-center">
                    <i class="bi bi-bar-chart-steps me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fs-6 fw-bold">Skor 6 Kriteria (Matriks Keputusan)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <tbody>
                        <tr>
                            <td><strong>C1. Jarak dari Pusat Kota</strong></td>
                            <td class="text-end fw-semibold font-mono">{{ number_format($destinasi->c1_jarak, 1, ',', '.') }} km</td>
                        </tr>
                        <tr>
                            <td><strong>C2. {{ $labelC2 }}</strong></td>
                            <td class="text-end fw-semibold font-mono">Rp {{ number_format($destinasi->c2_biaya * 1000, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>C3. Kelengkapan Fasilitas</strong></td>
                            <td class="text-end">
                                <span class="badge bg-light text-dark border fw-bold font-mono">{{ $destinasi->c3_fasilitas }} / 5</span>
                                <small class="text-muted d-block">{{ $destinasi->total_fasilitas ?? '-' }} fasilitas tercatat</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>C4. Keindahan Alam</strong></td>
                            <td class="text-end">
                                <span class="badge bg-light text-dark border fw-bold font-mono">{{ $destinasi->c4_keindahan }} / 5</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>C5. Kebersihan Lingkungan Pantai</strong></td>
                            <td class="text-end">
                                <span class="badge bg-light text-dark border fw-bold font-mono">{{ $destinasi->c5_kebersihan }} / 5</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>C6. Keragaman Kesesuaian Aktivitas</strong></td>
                            <td class="text-end">
                                <span class="badge bg-light text-dark border fw-bold font-mono">{{ $destinasi->c6_aktivitas ?? 5 }} / 5</span>
                                <small class="text-muted d-block">{{ $destinasi->total_aktivitas ?? '-' }} aktivitas tersedia</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($kontribusi !== null && (!empty($destinasi->fasilitas_list) || !empty($destinasi->aktivitas_list)))
                <div class="card card-custom mb-4">
                    <div class="card-header card-header-custom d-flex align-items-center">
                        <i class="bi bi-check2-circle me-2 text-success"></i>
                        <h5 class="card-title mb-0 fs-6 fw-bold">Daftar Fasilitas &amp; Wahana Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($destinasi->fasilitas_list))
                            <div class="mb-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-2">Fasilitas Tersedia:</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($destinasi->fasilitas_list as $f)
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                            <i class="bi bi-check2 me-1 text-success"></i>{{ $f }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (!empty($destinasi->aktivitas_list))
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase mb-2">Aktivitas / Wahana:</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($destinasi->aktivitas_list as $a)
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-medium">
                                            <i class="bi bi-water me-1 text-primary"></i>{{ $a }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            @if ($kontribusi !== null)
                <div class="card card-custom h-100">
                    <div class="card-header card-header-custom d-flex align-items-center">
                        <i class="bi bi-calculator me-2 text-primary"></i>
                        <h5 class="card-title mb-0 fs-6 fw-bold">Rincian perhitungan SAW</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light border py-2 small mb-3">
                            <i class="bi bi-person-badge me-1 text-primary"></i>
                            Minat <strong>{{ $sesi['profil']['minat'] }}</strong> &bull;
                            Anggaran <strong>{{ $sesi['profil']['anggaran'] }}</strong> &bull;
                            Jarak <strong>{{ $sesi['profil']['jarak'] }}</strong>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle table-custom">
                                <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th class="text-center">Sifat</th>
                                    <th class="text-end">Bobot (W)</th>
                                    <th class="text-end">Normalisasi (R)</th>
                                    <th class="text-end">Kontribusi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sifatKriteria = ['Cost', 'Cost', 'Benefit', 'Benefit', 'Benefit', 'Benefit'];
                                    $namaKriteria = [
                                        'C1 Jarak',
                                        'C2 ' . $labelC2,
                                        'C3 Fasilitas',
                                        'C4 Keindahan',
                                        'C5 Kebersihan',
                                        'C6 Keragaman Kesesuaian Aktivitas',
                                    ];
                                @endphp
                                @foreach ($kontribusi as $i => $k)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $namaKriteria[$i] ?? ('C' . ($i + 1)) }}</span>
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $sifatKriteria[$i] }}
                                        </td>
                                        <td class="text-end font-mono">{{ number_format($bobot[$i], 4, ',', '.') }}</td>
                                        <td class="text-end font-mono">{{ number_format($normalisasi[$i], 4, ',', '.') }}</td>
                                        <td class="text-end fw-semibold text-primary font-mono">{{ number_format($k, 4, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="4" class="text-end">Total Nilai Preferensi (Vi):</td>
                                    <td class="text-end text-primary fs-6 font-mono">{{ number_format($nilaiV, 4, ',', '.') }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-custom mb-4">
                    <div class="card-header card-header-custom d-flex align-items-center">
                        <i class="bi bi-check2-circle me-2 text-success"></i>
                        <h5 class="card-title mb-0 fs-6 fw-bold">Daftar Fasilitas &amp; Wahana Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($destinasi->fasilitas_list))
                            <div class="mb-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-2">Fasilitas Tersedia:</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($destinasi->fasilitas_list as $f)
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                            <i class="bi bi-check2 me-1 text-success"></i>{{ $f }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (!empty($destinasi->aktivitas_list))
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase mb-2">Aktivitas / Wahana:</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($destinasi->aktivitas_list as $a)
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-medium">
                                            <i class="bi bi-water me-1 text-primary"></i>{{ $a }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
