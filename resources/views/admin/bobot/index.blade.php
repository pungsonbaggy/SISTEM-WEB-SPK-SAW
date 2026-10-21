@extends('layouts.app')

@section('judul', 'Bobot Segmen — Admin')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Bobot Kriteria per Segmen</h1>
            <p class="text-muted small mb-0">
                Sepuluh baris bobot (Tabel 3.10 skripsi). Setiap baris harus berjumlah 1,0000;
                sistem menormalkan ulang otomatis saat disimpan.
            </p>
        </div>
        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-sm">Data Destinasi</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.bobot.update') }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width:90px">Segmen</th>
                        <th style="width:150px">Nilai</th>
                        @foreach ($kriteria as $kode => $nama)
                            <th class="text-center" style="width:110px">{{ $kode }}</th>
                        @endforeach
                        <th class="text-center" style="width:80px">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($semua as $segmen => $daftar)
                        <tr class="table-secondary">
                            <td colspan="9" class="fw-semibold">{{ ucfirst($segmen) }}</td>
                        </tr>
                        @foreach ($daftar as $baris)
                            <tr>
                                <td></td>
                                <td>{{ $baris->nilai }}</td>
                                @foreach (['w1', 'w2', 'w3', 'w4', 'w5', 'w6'] as $kolom)
                                    <td>
                                        <input type="number" step="0.0001" min="0" max="1"
                                               class="form-control form-control-sm text-center"
                                               name="bobot[{{ $baris->id }}][]"
                                               value="{{ number_format($baris->{$kolom}, 4, '.', '') }}">
                                    </td>
                                @endforeach
                                <td class="text-center nilai-v">
                                    {{ number_format($baris->w1 + $baris->w2 + $baris->w3 + $baris->w4 + $baris->w5 + $baris->w6, 4, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button class="btn text-white mt-3" style="background-color:var(--laut)" type="submit">
            Simpan bobot
        </button>
    </form>
@endsection
