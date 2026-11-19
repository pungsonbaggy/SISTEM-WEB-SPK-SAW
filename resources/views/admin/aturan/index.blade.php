@extends('layouts.app')

@section('judul', 'Aturan Kelayakan – Admin')

@section('konten')
    <div class="admin-nav-bar">
        <div class="admin-nav-tabs">
            <a href="{{ route('admin.destinasi.index') }}" class="admin-tab-link">
                <i class="bi bi-geo-alt"></i> Data Destinasi
            </a>
            <a href="{{ route('admin.bobot.index') }}" class="admin-tab-link">
                <i class="bi bi-sliders"></i> Bobot Segmen
            </a>
            <a href="{{ route('admin.aturan.index') }}" class="admin-tab-link active">
                <i class="bi bi-funnel"></i> Aturan Kelayakan
            </a>
            <a href="{{ route('admin.analisis.index') }}" class="admin-tab-link">
                <i class="bi bi-bar-chart-line"></i> Analisis Sebaran
            </a>
        </div>
    </div>

    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Aturan Kelayakan</h1>
        <p class="text-muted small mb-0">
            Batas minimal skor C6 agar destinasi diikutkan dalam perangkingan.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.aturan.update') }}">
        @csrf
        @method('PUT')

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead>
                    <tr>
                        <th style="width: 180px;">Minat Wisatawan</th>
                        <th style="width: 110px;">Kriteria</th>
                        <th style="width: 240px;">Operator</th>
                        <th style="width: 160px;">Ambang Minimum</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($minat as $m)
                        @php $baris = $aturan->firstWhere('minat', $m); @endphp
                        <tr>
                            <td><strong>{{ $m }}</strong></td>
                            <td><span class="badge bg-light text-dark border font-mono">{{ $baris->kriteria ?? 'C6' }}</span></td>
                            <td class="text-muted small">&ge; (lebih besar atau sama dengan)</td>
                            <td>
                                <input type="number" min="1" max="5" class="form-control form-control-sm text-center font-mono bg-white"
                                       name="threshold[{{ $m }}]"
                                       value="{{ old('threshold.' . $m, $baris->threshold ?? 1) }}">
                            </td>
                            <td class="text-muted small">
                                {{ $baris->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button class="btn-marine mt-3" type="submit">
            <i class="bi bi-check-lg"></i> Simpan Aturan Kelayakan
        </button>
    </form>

    <div class="alert alert-light border mt-3 small mb-0 card-custom">
        <i class="bi bi-info-circle me-1 text-primary"></i>
        <strong>Catatan:</strong> Menaikkan ambang batas membuat destinasi yang dipertimbangkan semakin selektif.
        Jika semua destinasi tersaring, sistem menampilkan pesan bahwa tidak ada destinasi yang layak.
    </div>
@endsection
