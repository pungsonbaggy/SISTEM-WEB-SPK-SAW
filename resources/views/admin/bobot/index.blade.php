@extends('layouts.app')

@section('judul', 'Bobot Kriteria – Admin')

@section('konten')
    <div class="admin-nav-bar">
        <div class="admin-nav-tabs">
            <a href="{{ route('admin.destinasi.index') }}" class="admin-tab-link">
                <i class="bi bi-geo-alt"></i> Data Destinasi
            </a>
            <a href="{{ route('admin.bobot.index') }}" class="admin-tab-link active">
                <i class="bi bi-sliders"></i> Bobot Segmen
            </a>
            <a href="{{ route('admin.aturan.index') }}" class="admin-tab-link">
                <i class="bi bi-funnel"></i> Aturan Kelayakan
            </a>
            <a href="{{ route('admin.analisis.index') }}" class="admin-tab-link">
                <i class="bi bi-bar-chart-line"></i> Analisis Sebaran
            </a>
        </div>
    </div>

    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Bobot Kriteria per Segmen</h1>
        <p class="text-muted small mb-0">
            Sepuluh baris bobot segmen profil wisatawan. Setiap baris harus berjumlah 1,0000;
            sistem menormalkan ulang otomatis saat disimpan.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.bobot.update') }}">
        @csrf
        @method('PUT')

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 table-custom">
                    <thead>
                    <tr>
                        <th style="width:110px">Dimensi</th>
                        <th style="width:150px">Segmen Profil</th>
                        @foreach ($kriteria as $kode => $nama)
                            <th class="text-center" style="width:110px">{{ $kode }}</th>
                        @endforeach
                        <th class="text-center" style="width:95px">Total (ΣW)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($semua as $segmen => $daftar)
                        <tr class="table-light">
                            <td colspan="9" class="fw-bold text-uppercase small text-secondary px-3 py-2">{{ ucfirst($segmen) }}</td>
                        </tr>
                        @foreach ($daftar as $baris)
                            <tr>
                                <td></td>
                                <td class="fw-semibold">{{ $baris->nilai }}</td>
                                @foreach (['w1', 'w2', 'w3', 'w4', 'w5', 'w6'] as $kolom)
                                    <td>
                                        <input type="number" step="0.0001" min="0" max="1"
                                               class="form-control form-control-sm text-center font-mono"
                                               name="bobot[{{ $baris->id }}][]"
                                               value="{{ number_format($baris->{$kolom}, 4, '.', '') }}">
                                    </td>
                                @endforeach
                                <td class="text-center fw-bold text-success font-mono">
                                    {{ number_format($baris->w1 + $baris->w2 + $baris->w3 + $baris->w4 + $baris->w5 + $baris->w6, 4, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button class="btn-marine mt-3" type="submit">
            <i class="bi bi-check-lg"></i> Simpan Bobot Segmen
        </button>
    </form>
@endsection
