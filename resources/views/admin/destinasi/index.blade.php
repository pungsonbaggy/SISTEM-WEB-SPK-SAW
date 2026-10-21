@extends('layouts.app')

@section('judul', 'Data Destinasi — Admin')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Data Destinasi</h1>
            <p class="text-muted small mb-0">
                Sumber matriks keputusan (Tabel 3.12 dan 3.13 skripsi).
            </p>
        </div>
        <div>
            <a href="{{ route('admin.bobot.index') }}" class="btn btn-outline-secondary btn-sm">Bobot</a>
            <a href="{{ route('admin.aturan.index') }}" class="btn btn-outline-secondary btn-sm">Aturan</a>
            <a href="{{ route('admin.analisis.index') }}" class="btn btn-outline-secondary btn-sm">Analisis</a>
            <a href="{{ route('admin.destinasi.create') }}" class="btn btn-sm text-white"
               style="background-color:var(--laut)">+ Tambah</a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th class="text-end">C1 (km)</th>
                    <th class="text-end">C2 (rb)</th>
                    <th class="text-center">C3</th>
                    <th class="text-center">C4</th>
                    <th class="text-center">C5</th>
                    <th class="text-center">C6 (Snk/Div/Pnt/Plu)</th>
                    <th class="text-end">Kunjungan</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($destinasi as $d)
                    @php $skor = $d->skorAktivitas->pluck('skor', 'minat'); @endphp
                    <tr>
                        <td><code>{{ $d->kode }}</code></td>
                        <td>{{ $d->nama }}</td>
                        <td class="text-end">{{ number_format($d->c1_jarak, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($d->c2_biaya, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $d->c3_fasilitas }}</td>
                        <td class="text-center">{{ $d->c4_keindahan }}</td>
                        <td class="text-center">{{ $d->c5_kebersihan }}</td>
                        <td class="text-center">
                            {{ $skor['Snorkeling'] ?? '-' }}/{{ $skor['Diving'] ?? '-' }}/{{ $skor['Wisata Pantai'] ?? '-' }}/{{ $skor['Wisata Pulau'] ?? '-' }}
                        </td>
                        <td class="text-end">{{ number_format($d->kunjungan_2025, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.destinasi.edit', $d) }}"
                               class="btn btn-sm btn-outline-primary">Ubah</a>
                            <form method="POST" action="{{ route('admin.destinasi.destroy', $d) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus destinasi {{ $d->kode }}? Skor C6-nya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
