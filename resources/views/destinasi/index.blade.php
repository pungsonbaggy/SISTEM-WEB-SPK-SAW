@extends('layouts.app')

@section('judul', 'Daftar Destinasi Wisata Bahari – Kabupaten Pesawaran')

@push('gaya')
<style>
    .dest-card {
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.15s ease;
    }
    .dest-card:hover {
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }
    .dest-carousel .carousel-inner {
        border-radius: 12px 12px 0 0;
    }
    .dest-carousel img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .dest-carousel .carousel-indicators {
        margin-bottom: 0.4rem;
    }
    .dest-carousel .carousel-indicators button {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        border: none;
        opacity: 0.5;
    }
    .dest-carousel .carousel-indicators button.active {
        opacity: 1;
        background-color: #fff;
    }
    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 5px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 500;
    }
    .info-chip i { font-size: 0.8rem; }
    .aktivitas-badge {
        font-size: 0.7rem;
        padding: 0.16rem 0.48rem;
        background: #e6f4f1;
        color: var(--primary);
        border-radius: 5px;
        font-weight: 600;
    }
</style>
@endpush

@section('konten')
    <div class="mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div>
                <h1 class="h3 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Destinasi Wisata Bahari Pesawaran</h1>
                <p class="text-muted mb-0">
                    Informasi umum {{ $totalDestinasi }} destinasi wisata bahari di Kabupaten Pesawaran, Lampung.
                </p>
            </div>
            <a href="{{ route('beranda') }}" class="btn-marine py-2 px-3">
                Pilih Preferensi
            </a>
        </div>
    </div>

    <div class="row g-4">
        @foreach ($destinasi as $d)
            @php
                $fotos = $d->daftar_foto;
                $carouselId = 'carousel-' . $d->kode;
            @endphp
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card card-custom dest-card h-100">
                    <div id="{{ $carouselId }}" class="carousel slide dest-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                        @if (count($fotos) > 1)
                            <div class="carousel-indicators">
                                @foreach ($fotos as $fi => $foto)
                                    <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $fi }}"
                                            class="{{ $fi === 0 ? 'active' : '' }}" aria-label="Slide {{ $fi + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                        <div class="carousel-inner">
                            @foreach ($fotos as $fi => $foto)
                                <div class="carousel-item {{ $fi === 0 ? 'active' : '' }}">
                                    <img src="{{ $foto }}" alt="{{ $d->nama }} - Foto {{ $fi + 1 }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-dark bg-opacity-75 fw-bold px-2 py-1 font-mono">{{ $d->kode }}</span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-3">
                        <h5 class="card-title fw-bold mb-2" style="font-size: 0.96rem;">{{ $d->nama }}</h5>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem; line-height: 1.4;">
                            {{ Str::limit($d->deskripsi, 100) }}
                        </p>

                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="info-chip">
                                <i class="bi bi-geo-alt text-primary"></i>
                                {{ number_format($d->c1_jarak, 0, ',', '.') }} km
                            </span>
                            <span class="info-chip">
                                <i class="bi bi-cash-stack text-success"></i>
                                Rp {{ number_format($d->c2_biaya * 1000, 0, ',', '.') }}
                            </span>
                            <span class="info-chip">
                                <i class="bi bi-building-check text-info"></i>
                                {{ $d->total_fasilitas }} fasilitas
                            </span>
                        </div>

                        @if (!empty($d->aktivitas_list))
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach ($d->aktivitas_list as $akt)
                                    <span class="aktivitas-badge">{{ $akt }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if (!empty($d->fasilitas_list))
                            <div class="mt-auto">
                                <button class="btn btn-sm btn-light w-100 text-start small collapsed"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#fas-{{ $d->kode }}">
                                    <i class="bi bi-chevron-down me-1"></i>Lihat {{ count($d->fasilitas_list) }} fasilitas
                                </button>
                                <div class="collapse mt-2" id="fas-{{ $d->kode }}">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($d->fasilitas_list as $f)
                                            <span class="badge bg-light text-dark border small">
                                                <i class="bi bi-check text-success me-1"></i>{{ $f }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                        <a href="{{ route('destinasi.show', $d->kode) }}" class="btn btn-outline-dark btn-sm w-100" style="border-radius: 7px;">
                            <i class="bi bi-info-circle me-1"></i> Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
