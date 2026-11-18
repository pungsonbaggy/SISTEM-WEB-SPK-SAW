@extends('layouts.app')

@section('judul', 'Beranda — SPK Wisata Bahari Kabupaten Pesawaran')

@push('gaya')
<style>
    .hero-full {
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        margin-top: -1.5rem;
        background:
            linear-gradient(180deg, rgba(6, 28, 33, 0.76) 0%, rgba(10, 46, 54, 0.58) 55%, rgba(6, 28, 33, 0.88) 100%),
            url('/images/destinasi/a1-1.jpg')
            center / cover no-repeat;
        padding: 4.75rem 0 5.5rem;
        min-height: 370px;
        display: flex;
        align-items: center;
        color: #ffffff;
    }

    .hero-full .hero-title {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(1.75rem, 3.6vw, 2.45rem);
        font-weight: 700;
        line-height: 1.25;
        letter-spacing: -0.02em;
        text-shadow: 0 2px 14px rgba(0, 0, 0, 0.35);
        max-width: 680px;
        margin-bottom: 0.65rem;
    }

    .hero-full .hero-subtitle {
        color: rgba(255, 255, 255, 0.88);
        font-size: 1rem;
        max-width: 620px;
        font-weight: 400;
        margin-bottom: 0;
    }

    .hero-full .hero-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.55rem;
        margin-top: 1.35rem;
    }

    .hero-full .hero-meta span {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.24);
        color: #f8fafc;
        font-size: 0.82rem;
        font-weight: 500;
        padding: 0.38rem 0.85rem;
        border-radius: 8px;
    }

    .hero-full .hero-meta a {
        background: #ffffff;
        color: var(--primary-dark);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .hero-full .hero-meta a:hover {
        background: #38bdf8;
        color: #061c21;
    }

    .bento-form-wrap {
        margin-top: -2.25rem;
        position: relative;
        z-index: 5;
    }

    .bento-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 1.4rem;
        box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.08);
    }

    .bento-header {
        margin-bottom: 1.1rem;
    }

    .bento-title-row {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.3rem;
    }

    .bento-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .bento-desc {
        font-size: 0.86rem;
        color: var(--ink-muted);
        margin-bottom: 0;
    }

    .minat-card-label {
        height: 100%;
        flex-direction: column;
        align-items: stretch;
        padding: 1.05rem;
        gap: 0.75rem;
    }

    .minat-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .submit-bar {
        background: var(--primary-dark);
        color: #ffffff;
        border-radius: 14px;
        padding: 1.15rem 1.5rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-submit-saw {
        font-family: 'Poppins', sans-serif;
        background: #38bdf8;
        color: #061c21;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        padding: 0.78rem 1.75rem;
        border-radius: 10px;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit-saw:hover {
        background: #7dd3fc;
        color: #061c21;
    }
</style>
@endpush

@section('konten')

<div class="hero-full">
    <div class="container">
        <h1 class="hero-title">
            Sistem Pendukung Keputusan<br>Pemilihan Destinasi Wisata Bahari
        </h1>
        <p class="hero-subtitle">
            Temukan destinasi pantai dan kepulauan di Kabupaten Pesawaran yang paling sesuai dengan minat aktivitas, alokasi anggaran, serta jarak tempuh Anda.
        </p>
        <div class="hero-meta">
            <span>{{ $jumlahDestinasi }} Alternatif Destinasi</span>
            <span>6 Kriteria Evaluasi</span>
            <span>Bobot Empiris 60 Responden</span>
            <a href="{{ route('destinasi.index') }}">Lihat Katalog Destinasi &rarr;</a>
        </div>
    </div>
</div>

<div class="bento-form-wrap">

    @if ($errors->any())
        <div class="alert alert-danger border-0 border-start border-danger border-4 mb-4 card-custom">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-octagon-fill text-danger fs-5 me-3 mt-1"></i>
                <div>
                    <strong class="d-block mb-1">Mohon lengkapi profil preferensi Anda:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('rekomendasi.store') }}">
        @csrf

        @php
            $minatIcons = [
                'Snorkeling'    => ['icon' => 'bi-water',    'desc' => 'Menyelam permukaan & spot terumbu karang'],
                'Diving'        => ['icon' => 'bi-moisture', 'desc' => 'Penyelaman kedalaman & eksplorasi biota laut'],
                'Wisata Pantai' => ['icon' => 'bi-sun',      'desc' => 'Rekreasi pasir pantai, berenang & bersantai'],
                'Wisata Pulau'  => ['icon' => 'bi-compass',  'desc' => 'Jelajah kepulauan (island hopping) & perahu'],
            ];

            $anggaranIcons = [
                'Rendah' => ['icon' => 'bi-wallet2',     'label' => 'Rendah', 'range' => 'Rp 10.000 – Rp 50.000'],
                'Sedang' => ['icon' => 'bi-cash-stack',  'label' => 'Sedang', 'range' => 'Rp 50.000 – Rp 150.000'],
                'Tinggi' => ['icon' => 'bi-credit-card', 'label' => 'Tinggi', 'range' => 'Rp 150.000 – Rp 220.000'],
            ];

            $jarakIcons = [
                'Dekat'  => ['icon' => 'bi-geo-alt',    'label' => 'Dekat',  'range' => '< 15 km dari pusat kota'],
                'Sedang' => ['icon' => 'bi-signpost-2', 'label' => 'Sedang', 'range' => '15 – 30 km dari pusat kota'],
                'Jauh'   => ['icon' => 'bi-map',        'label' => 'Jauh',   'range' => '> 30 km dari pusat kota'],
            ];
        @endphp

        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="bento-card">
                    <div class="bento-header">
                        <div class="bento-title-row">
                            <span class="step-badge">Langkah 1</span>
                            <h2 class="bento-title">Minat Aktivitas Utama</h2>
                        </div>
                        <p class="bento-desc">Pilih jenis pengalaman wisata bahari yang paling Anda utamakan dalam kunjungan ini.</p>
                    </div>

                    <div class="row g-2">
                        @foreach ($minat as $m)
                            <div class="col-sm-6 col-lg-3">
                                <input class="form-check-input d-none" type="radio" name="minat"
                                       id="minat-{{ $loop->index }}" value="{{ $m }}"
                                       @checked(old('minat', $loop->first ? $m : null) === $m)>
                                <label class="option-card-label minat-card-label" for="minat-{{ $loop->index }}">
                                    <div class="minat-card-top">
                                        <div class="option-card-icon">
                                            <i class="bi {{ $minatIcons[$m]['icon'] ?? 'bi-water' }}"></i>
                                        </div>
                                        <span class="option-check-indicator">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $m }}</div>
                                        <div class="text-muted small" style="font-size: 0.78rem; line-height: 1.35;">
                                            {{ $minatIcons[$m]['desc'] ?? '' }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bento-card h-100">
                    <div class="bento-header">
                        <div class="bento-title-row">
                            <span class="step-badge">Langkah 2</span>
                            <h2 class="bento-title">Anggaran Perjalanan</h2>
                        </div>
                        <p class="bento-desc">Rentang biaya masuk &amp; penyeberangan per orang (minimal Rp 10.000 s/d maksimal Rp 220.000).</p>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach ($anggaran as $a)
                            <div>
                                <input class="form-check-input d-none" type="radio" name="anggaran"
                                       id="anggaran-{{ $loop->index }}" value="{{ $a }}"
                                       @checked(old('anggaran', $loop->first ? $a : null) === $a)>
                                <label class="option-card-label" for="anggaran-{{ $loop->index }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="option-card-icon">
                                            <i class="bi {{ $anggaranIcons[$a]['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $anggaranIcons[$a]['label'] }}</div>
                                            <div class="text-muted small" style="font-size: 0.78rem;">{{ $anggaranIcons[$a]['range'] }}</div>
                                        </div>
                                    </div>
                                    <span class="option-check-indicator">
                                        <i class="bi bi-check-lg"></i>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bento-card h-100">
                    <div class="bento-header">
                        <div class="bento-title-row">
                            <span class="step-badge">Langkah 3</span>
                            <h2 class="bento-title">Preferensi Jarak Tempuh</h2>
                        </div>
                        <p class="bento-desc">Titik ukur jarak dihitung dari Tugu Adipura Bandar Lampung melalui jalur pesisir Pesawaran.</p>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach ($jarak as $j)
                            <div>
                                <input class="form-check-input d-none" type="radio" name="jarak"
                                       id="jarak-{{ $loop->index }}" value="{{ $j }}"
                                       @checked(old('jarak', $loop->first ? $j : null) === $j)>
                                <label class="option-card-label" for="jarak-{{ $loop->index }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="option-card-icon">
                                            <i class="bi {{ $jarakIcons[$j]['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $jarakIcons[$j]['label'] }}</div>
                                            <div class="text-muted small" style="font-size: 0.78rem;">{{ $jarakIcons[$j]['range'] }}</div>
                                        </div>
                                    </div>
                                    <span class="option-check-indicator">
                                        <i class="bi bi-check-lg"></i>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="submit-bar mb-4">
            <div>
                <div class="fw-semibold text-white">Siap menghitung peringkat rekomendasi destinasi?</div>
                <div class="small" style="color: #94a3b8;">
                    Sistem akan menggabungkan bobot ketiga segmen profil Anda dan memeringkatkan seluruh destinasi secara objektif.
                </div>
            </div>
            <button class="btn-submit-saw" type="submit">
                <i class="bi bi-sliders2"></i> Hitung &amp; Dapatkan Rekomendasi
            </button>
        </div>
    </form>

</div>

@endsection
