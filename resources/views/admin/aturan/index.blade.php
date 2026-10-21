@extends('layouts.app')

@section('judul', 'Aturan Kelayakan — Admin')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Aturan Kelayakan</h1>
            <p class="text-muted small mb-0">
                Batas minimal skor C6 agar destinasi diikutkan dalam perangkingan (Tabel 3.24 skripsi).
            </p>
        </div>
        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-sm">Data Destinasi</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.aturan.update') }}">
        @csrf
        @method('PUT')

        <div class="card">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Minat</th>
                    <th>Kriteria</th>
                    <th style="width:140px">Operator</th>
                    <th style="width:160px">Ambang minimum</th>
                    <th>Keterangan</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($minat as $m)
                    @php $baris = $aturan->firstWhere('minat', $m); @endphp
                    <tr>
                        <td><strong>{{ $m }}</strong></td>
                        <td>{{ $baris->kriteria ?? 'C6' }}</td>
                        <td>&ge; (lebih besar atau sama dengan)</td>
                        <td>
                            <input type="number" min="1" max="5" class="form-control form-control-sm"
                                   name="threshold[{{ $m }}]"
                                   value="{{ old('threshold.' . $m, $baris->threshold ?? 2) }}">
                        </td>
                        <td class="text-muted small">
                            {{ $baris->keterangan ?? '-' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn text-white mt-3" style="background-color:var(--laut)" type="submit">
            Simpan aturan
        </button>
    </form>

    <div class="alert alert-light border mt-3 small mb-0">
        <strong>Catatan:</strong> menaikkan ambang membuat destinasi yang dipertimbangkan semakin sedikit.
        Jika semua destinasi tersaring, sistem menampilkan pesan bahwa tidak ada destinasi layak
        (lihat skenario pengujian nomor 4 pada Tabel 3.26).
    </div>
@endsection
