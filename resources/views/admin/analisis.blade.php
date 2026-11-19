@extends('layouts.app')

@section('judul', 'Analisis Sebaran Rekomendasi – Admin')

@section('konten')
    <div class="admin-nav-bar">
        <div class="admin-nav-tabs">
            <a href="{{ route('admin.destinasi.index') }}" class="admin-tab-link">
                <i class="bi bi-geo-alt"></i> Data Destinasi
            </a>
            <a href="{{ route('admin.bobot.index') }}" class="admin-tab-link">
                <i class="bi bi-sliders"></i> Bobot Segmen
            </a>
            <a href="{{ route('admin.aturan.index') }}" class="admin-tab-link">
                <i class="bi bi-funnel"></i> Aturan Kelayakan
            </a>
            <a href="{{ route('admin.analisis.index') }}" class="admin-tab-link active">
                <i class="bi bi-bar-chart-line"></i> Analisis Sebaran
            </a>
        </div>
    </div>

    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Analisis Sebaran Rekomendasi</h1>
        <p class="text-muted small mb-0">
            Hasil simulasi {{ $totalProfil }} kombinasi profil (4 minat &times; 3 anggaran &times; 3 jarak).
            Menunjukkan frekuensi kemunculan setiap destinasi di lima besar rekomendasi.
        </p>
    </div>

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-custom">
                <thead>
                <tr>
                    <th style="width: 85px;">Kode</th>
                    <th>Destinasi</th>
                    <th class="text-center" style="width: 150px;">Masuk Top-5</th>
                    <th class="text-center" style="width: 260px;">Persentase Exposure</th>
                    <th class="text-center" style="width: 130px;">Peringkat 1</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($baris as $b)
                    <tr>
                        <td><span class="badge bg-dark text-white font-mono">{{ $b['kode'] }}</span></td>
                        <td><strong>{{ $b['nama'] }}</strong></td>
                        <td class="text-center font-mono fw-semibold">{{ $b['top5'] }} / {{ $totalProfil }}</td>
                        <td class="text-center">
                            <div class="progress" style="height: 20px; border-radius: 6px; background: #e2e8f0;">
                                <div class="progress-bar fw-semibold font-mono" role="progressbar"
                                     style="width: {{ max($b['persen'], 8) }}%; background-color: var(--primary); font-size: 0.75rem;"
                                     aria-valuenow="{{ $b['persen'] }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $b['persen'] }}%
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($b['juara'] > 0)
                                <span class="badge bg-light text-dark border font-mono px-2 py-1">{{ $b['juara'] }}x</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-light border mt-3 small mb-0 card-custom">
        <i class="bi bi-lightbulb me-1 text-warning"></i>
        <strong>Cara membaca:</strong> Kolom &ldquo;Masuk Top-5&rdquo; menghitung berapa kali destinasi
        muncul dalam lima besar dari {{ $totalProfil }} kombinasi profil yang diuji. Destinasi dengan
        persentase tinggi membuktikan bahwa sistem SAW berhasil mendorong diversifikasi dan pemerataan kunjungan wisata.
    </div>
@endsection
