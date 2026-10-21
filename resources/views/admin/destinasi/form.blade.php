@extends('layouts.app')

@section('judul', ($destinasi->exists ? 'Ubah' : 'Tambah') . ' Destinasi — Admin')

@section('konten')
    @php
        // Daftar kolom C3-C5 agar tidak menulis ulang tiga blok form.
        $fieldKriteria = [
            ['kolom' => 'c3_fasilitas', 'label' => 'C3 Kelengkapan fasilitas'],
            ['kolom' => 'c4_keindahan', 'label' => 'C4 Keindahan alam'],
            ['kolom' => 'c5_kebersihan', 'label' => 'C5 Kebersihan pantai'],
        ];
    @endphp

    <a href="{{ route('admin.destinasi.index') }}" class="text-decoration-none small">&larr; Kembali</a>
    <h1 class="h3 mt-2 mb-3">{{ $destinasi->exists ? 'Ubah' : 'Tambah' }} Destinasi</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $destinasi->exists ? route('admin.destinasi.update', $destinasi) : route('admin.destinasi.store') }}">
        @csrf
        @if ($destinasi->exists)
            @method('PUT')
        @endif

        <div class="card mb-4">
            <div class="card-header">Identitas</div>
            <div class="card-body row g-3">
                <div class="col-md-2">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control"
                           value="{{ old('kode', $destinasi->kode) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama destinasi</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $destinasi->nama) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kunjungan 2025</label>
                    <input type="number" name="kunjungan_2025" class="form-control"
                           value="{{ old('kunjungan_2025', $destinasi->kunjungan_2025) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Skor kriteria C1&ndash;C5</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">C1 Jarak (km)</label>
                    <input type="number" step="0.01" name="c1_jarak" class="form-control"
                           value="{{ old('c1_jarak', $destinasi->c1_jarak) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">C2 Biaya masuk (ribu rupiah)</label>
                    <input type="number" step="0.01" name="c2_biaya" class="form-control"
                           value="{{ old('c2_biaya', $destinasi->c2_biaya) }}" required>
                </div>

                @foreach ($fieldKriteria as $info)
                    <div class="col-md-4">
                        <label class="form-label">{{ $info['label'] }}</label>
                        <select name="{{ $info['kolom'] }}" class="form-select" required>
                            @foreach (range(1, 5) as $nilai)
                                <option value="{{ $nilai }}"
                                    @selected((int) old($info['kolom'], $destinasi->{$info['kolom']}) === $nilai)>
                                    {{ $nilai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Skor C6 — Kesesuaian aktivitas per minat</div>
            <div class="card-body">
                <p class="text-muted small">
                    1 = tidak mendukung, 5 = sangat mendukung. Nilai ini yang dipakai
                    untuk menyaring destinasi sebelum perangkingan.
                </p>
                <div class="row g-3">
                    @foreach ($minat as $m)
                        <div class="col-md-3">
                            <label class="form-label">{{ $m }}</label>
                            <select name="skor[{{ $m }}]" class="form-select" required>
                                @foreach (range(1, 5) as $nilai)
                                    <option value="{{ $nilai }}"
                                        @selected((int) old('skor.' . $m, $skor[$m] ?? 3) === $nilai)>
                                        {{ $nilai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button class="btn text-white" style="background-color:var(--laut)" type="submit">
            {{ $destinasi->exists ? 'Simpan perubahan' : 'Tambah destinasi' }}
        </button>
    </form>
@endsection
