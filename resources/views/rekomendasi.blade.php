@extends('layouts.app')

@section('judul', 'Hasil Rekomendasi — SPK Wisata Bahari')

@push('gaya')
<style>
    .spotlight-card {
        background: #ffffff;
        border: 2px solid var(--primary);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 28px -12px rgba(10, 46, 54, 0.16);
    }

    .spotlight-img-wrap {
        position: relative;
        height: 100%;
        min-height: 220px;
        background: #0f172a;
    }

    .spotlight-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .spotlight-rank-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--primary-dark);
        color: #38bdf8;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding: 0.32rem 0.7rem;
        border-radius: 7px;
        border: 1px solid rgba(56, 189, 248, 0.35);
    }

    .spotlight-score-box {
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: 12px;
        padding: 1rem 1.15rem;
        text-align: center;
    }

    .spotlight-score-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1.15;
    }

    .rank-badge {
        font-family: 'JetBrains Mono', monospace;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .rank-badge.rank-top {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }

    .activity-tag {
        font-size: 0.73rem;
        padding: 0.18rem 0.5rem;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-weight: 500;
    }

    .nilai-v-box {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        font-size: 0.96rem;
        color: var(--primary);
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        padding: 0.28rem 0.65rem;
        border-radius: 7px;
        display: inline-block;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 0.55rem 0.75rem;
        text-align: center;
    }

    .metric-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
    }

    .metric-val {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--ink);
    }
</style>
@endpush

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Hasil Rekomendasi Destinasi Wisata Bahari</h1>
            <p class="text-muted mb-0">
                Menampilkan urutan peringkat dari <strong>{{ $jumlah_layak }} destinasi</strong> yang dievaluasi berdasarkan profil preferensi Anda.
            </p>
        </div>
        <a href="{{ route('beranda') }}" class="btn btn-outline-dark btn-sm px-3 py-2 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-sliders2 me-1"></i> Ubah Profil Preferensi
        </a>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2">
                    Minat: <strong>{{ $profil['minat'] }}</strong>
                </span>
                <span class="badge bg-light text-dark border px-3 py-2">
                    Anggaran: <strong>{{ $profil['anggaran'] }}</strong>
                </span>
                <span class="badge bg-light text-dark border px-3 py-2">
                    Jarak: <strong>{{ $profil['jarak'] }}</strong>
                </span>
            </div>
            <span class="badge bg-light text-secondary border font-mono px-2 py-1">Total Bobot (ΣW) = 1,0000</span>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold mb-0" style="font-family: 'Poppins', sans-serif;">5 Rekomendasi Destinasi Teratas</h2>
        <span class="text-muted small">Peringkat 1 s/d 5</span>
    </div>

    @php
        $top5 = array_slice($hasil, 0, 5);
        $juara1 = $top5[0] ?? null;
        $top2to5 = array_slice($top5, 1);
    @endphp

    @if ($juara1)
        @php $aktJuara = $juara1['aktivitas_list'] ?? []; @endphp
        <div class="spotlight-card mb-3">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-4 col-lg-4">
                    <div class="spotlight-img-wrap">
                        @if ($juara1['foto'])
                            <img src="{{ $juara1['foto'] }}" alt="{{ $juara1['nama'] }}">
                        @endif
                        <span class="spotlight-rank-tag">#01 &bull; REKOMENDASI UTAMA</span>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8">
                    <div class="p-3 p-lg-4 h-100 d-flex flex-column justify-content-between">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="badge bg-dark font-mono text-white">{{ $juara1['kode'] }}</span>
                                    <h3 class="h4 fw-bold mb-0" style="font-family: 'Poppins', sans-serif;">{{ $juara1['nama'] }}</h3>
                                </div>
                                <p class="text-muted small mb-2">
                                    {{ $juara1['deskripsi'] ?: 'Destinasi wisata bahari dengan skor kecocokan tertinggi untuk profil Anda.' }}
                                </p>
                                <div class="d-flex flex-wrap gap-1 align-items-center mb-3">
                                    @foreach ($aktJuara as $akt)
                                        <span class="activity-tag">{{ str_replace(' / Island Hopping', '', $akt) }}</span>
                                    @endforeach
                                    <span class="text-muted small ms-1">
                                        &bull; {{ $juara1['total_fasilitas'] ?? $juara1['c3_fasilitas'] }} fasilitas tersedia
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="spotlight-score-box">
                                    <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">Nilai Preferensi (Vi)</div>
                                    <div class="spotlight-score-num">{{ number_format($juara1['v'], 4, ',', '.') }}</div>
                                    <div class="small text-success fw-semibold mt-1">Peringkat 1 Terbaik</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-2 pt-2 border-top">
                            <div class="metric-grid flex-grow-1">
                                <div>
                                    <div class="metric-label">Jarak (C1)</div>
                                    <div class="metric-val">{{ $juara1['c1_jarak'] }} km</div>
                                </div>
                                <div>
                                    <div class="metric-label">Biaya (C2)</div>
                                    <div class="metric-val">Rp {{ number_format($juara1['c2_biaya'], 0, ',', '.') }}rb</div>
                                </div>
                                <div>
                                    <div class="metric-label">Fasilitas (C3)</div>
                                    <div class="metric-val">{{ $juara1['c3_fasilitas'] }}/5</div>
                                </div>
                                <div>
                                    <div class="metric-label">Keindahan (C4)</div>
                                    <div class="metric-val">{{ $juara1['c4_keindahan'] }}/5</div>
                                </div>
                            </div>
                            <a href="{{ route('destinasi.show', $juara1['kode']) }}" class="btn-marine py-2 px-3">
                                Detail <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        @foreach ($top2to5 as $idx => $row)
            @php
                $rankNum = $idx + 2;
                $aktivitas = $row['aktivitas_list'] ?? [];
            @endphp
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-body p-3 px-lg-4">
                        <div class="row align-items-center gy-3 gx-2 gx-lg-3">
                            <div class="col-12 col-md-5 col-xl-5 d-flex align-items-center gap-3">
                                <div class="rank-badge {{ $rankNum <= 3 ? 'rank-top' : '' }} flex-shrink-0">
                                    0{{ $rankNum }}
                                </div>

                                @if ($row['foto'])
                                    <img src="{{ $row['foto'] }}" alt="{{ $row['nama'] }}"
                                         class="rounded d-none d-sm-block flex-shrink-0 border"
                                         style="width:76px; height:54px; object-fit:cover;">
                                @endif

                                <div class="flex-grow-1" style="min-width:0;">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <h3 class="h6 fw-bold mb-0 text-truncate">{{ $row['nama'] }}</h3>
                                        <span class="badge bg-light text-dark border small font-mono py-1 px-2">{{ $row['kode'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                        @foreach (array_slice($aktivitas, 0, 3) as $akt)
                                            <span class="activity-tag text-nowrap">{{ str_replace(' / Island Hopping', '', $akt) }}</span>
                                        @endforeach
                                        @if (count($aktivitas) > 3)
                                            <span class="badge bg-light text-secondary border small">+{{ count($aktivitas) - 3 }}</span>
                                        @endif
                                        <span class="text-muted small ms-1" style="font-size:0.74rem;">
                                            &bull; {{ $row['total_fasilitas'] ?? $row['c3_fasilitas'] }} fas.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-7 col-md-4 col-xl-4">
                                <div class="metric-grid">
                                    <div>
                                        <div class="metric-label">Jarak</div>
                                        <div class="metric-val">{{ $row['c1_jarak'] }} km</div>
                                    </div>
                                    <div>
                                        <div class="metric-label">Biaya</div>
                                        <div class="metric-val">Rp {{ number_format($row['c2_biaya'], 0, ',', '.') }}rb</div>
                                    </div>
                                    <div>
                                        <div class="metric-label">Fasilitas</div>
                                        <div class="metric-val">{{ $row['c3_fasilitas'] }}/5</div>
                                    </div>
                                    <div>
                                        <div class="metric-label">Indah</div>
                                        <div class="metric-val">{{ $row['c4_keindahan'] }}/5</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5 col-md-3 col-xl-3 d-flex align-items-center justify-content-end gap-2 gap-lg-3">
                                <div class="text-end">
                                    <div class="nilai-v-box mb-0">{{ number_format($row['v'], 4, ',', '.') }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;">Nilai Vi</div>
                                </div>
                                <a href="{{ route('destinasi.show', $row['kode']) }}"
                                   class="btn btn-outline-dark btn-sm px-3 text-nowrap fw-semibold" style="border-radius: 7px;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($hasil) > 5)
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-bold mb-0">Peringkat Destinasi Lanjutan (Peringkat 6 s/d {{ $jumlah_layak }})</h2>
                    <span class="text-muted small">Menampilkan destinasi alternatif berikutnya beserta nilai kriteria dan skor akhir (Nilai Preferensi Vi)</span>
                </div>
                <span class="badge bg-light text-secondary border">
                    {{ count($hasil) - 5 }} Destinasi
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead>
                    <tr>
                        <th class="text-center" style="width:65px">Rank</th>
                        <th>Destinasi</th>
                        <th class="text-center d-none d-md-table-cell">C1 Jarak</th>
                        <th class="text-center d-none d-md-table-cell">C2 Biaya</th>
                        <th class="text-center d-none d-md-table-cell">C3 Fasilitas</th>
                        <th class="text-center d-none d-md-table-cell">C4 Keindahan</th>
                        <th class="text-center d-none d-md-table-cell">C5 Kebersihan</th>
                        <th class="text-center d-none d-md-table-cell">C6 Aktivitas</th>
                        <th class="text-end" style="width:130px">Nilai Preferensi (Vi)</th>
                        <th class="text-end" style="width:90px">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (array_slice($hasil, 5) as $idx => $row)
                        @php $rank = $idx + 6; @endphp
                        <tr>
                            <td class="text-center">
                                <div class="rank-badge mx-auto">{{ sprintf('%02d', $rank) }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $row['nama'] }}</div>
                                <span class="badge bg-light text-secondary border small font-mono">{{ $row['kode'] }}</span>
                            </td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">{{ $row['c1_jarak'] }} km</td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">Rp {{ number_format($row['c2_biaya'], 0, ',', '.') }}rb</td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">{{ $row['c3_fasilitas'] }}/5</td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">{{ $row['c4_keindahan'] }}/5</td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">{{ $row['c5_kebersihan'] }}/5</td>
                            <td class="text-center d-none d-md-table-cell text-muted small font-mono">{{ $row['skor_c6'] }}/5</td>
                            <td class="text-end">
                                <span class="nilai-v-box" style="font-size:0.85rem;">{{ number_format($row['v'], 4, ',', '.') }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('destinasi.show', $row['kode']) }}"
                                   class="btn btn-sm btn-outline-dark px-3" style="border-radius: 6px;">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-header card-header-custom">
            <button class="btn btn-link text-decoration-none text-dark p-0 fw-bold d-flex justify-content-between align-items-center w-100" type="button"
                    data-bs-toggle="collapse" data-bs-target="#panelBobot">
                <span><i class="bi bi-sliders2 me-2 text-primary"></i>Bobot Kriteria yang Digunakan (W)</span>
                <i class="bi bi-chevron-down small text-muted"></i>
            </button>
        </div>
        <div class="collapse" id="panelBobot">
            <div class="card-body">
                <table class="table table-sm table-bordered text-center mb-2">
                    <thead class="table-light">
                    <tr>
                        @foreach ($kriteria as $kode => $nama)
                            <th>{{ $kode }}<br><span class="fw-normal text-muted" style="font-size:0.7rem;">{{ $nama }}</span></th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="font-mono">
                        @foreach ($bobot as $w)
                            <td class="fw-semibold">{{ number_format($w, 4, ',', '.') }}</td>
                        @endforeach
                        <td class="fw-bold text-success">{{ number_format(array_sum($bobot), 4, ',', '.') }}</td>
                    </tr>
                    </tbody>
                </table>
                <p class="text-muted small mb-0">
                    Bobot di atas merupakan rata-rata dari tiga segmen profil:
                    <strong>{{ $profil['minat'] }}</strong>, <strong>{{ $profil['anggaran'] }}</strong>, dan <strong>{{ $profil['jarak'] }}</strong>,
                    kemudian dinormalisasi sehingga total bobot tepat berjumlah 1,0000.
                </p>
            </div>
        </div>
    </div>
@endsection
