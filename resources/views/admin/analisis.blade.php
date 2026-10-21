@extends('layouts.app')

@section('judul', 'Analisis Sebaran Rekomendasi — Admin')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Analisis Sebaran Rekomendasi</h1>
            <p class="text-muted small mb-0">
                Hasil simulasi {{ $totalProfil }} kombinasi profil (4 minat &times; 3 anggaran &times; 3 jarak).
                Menunjukkan destinasi mana yang sering masuk lima besar.
            </p>
        </div>
        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-sm">Data Destinasi</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Destinasi</th>
                    <th class="text-end">Kunjungan 2025</th>
                    <th class="text-center">Masuk top-5</th>
                    <th class="text-center">Persentase</th>
                    <th class="text-center">Menjadi juara</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($baris as $b)
                    <tr>
                        <td><code>{{ $b['kode'] }}</code></td>
                        <td>{{ $b['nama'] }}</td>
                        <td class="text-end">{{ number_format($b['kunjungan'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ $b['top5'] }} / {{ $totalProfil }}</td>
                        <td class="text-center">
                            <div class="progress" style="height:16px">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $b['persen'] }}%; background-color:var(--laut)">
                                    {{ $b['persen'] }}%
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $b['juara'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-light border mt-3 small mb-0">
        <strong>Cara membaca:</strong> kolom &ldquo;masuk top-5&rdquo; menghitung berapa kali destinasi
        muncul dalam lima besar dari {{ $totalProfil }} kombinasi profil. Destinasi dengan kunjungan
        rendah tetapi persentase tinggi berarti sistem mendorong pemerataan kunjungan.
    </div>
@endsection
