@extends('layouts.app')

@section('judul', 'Beranda — SPK Wisata Bahari Pesawaran')

@section('konten')
    <div class="hero mb-4">
        <h1 class="h3 mb-2">Temukan destinasi wisata bahari yang sesuai denganmu</h1>
        <p class="mb-0" style="opacity:.9">
            Isi tiga profil di bawah ini. Sistem akan menyaring {{ $jumlahDestinasi }} destinasi
            wisata bahari Kabupaten Pesawaran, lalu memeringkatnya dengan metode
            <strong>Simple Additive Weighting</strong> sesuai prioritasmu.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali isian berikut:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rekomendasi.store') }}">
        @csrf
        <div class="row g-3">
            {{-- ------------------------------------------------ minat --}}
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">1. Minat aktivitas</div>
                    <div class="card-body">
                        <p class="text-muted small">Aktivitas utama yang ingin kamu lakukan.</p>
                        @foreach ($minat as $m)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="minat"
                                       id="minat-{{ $loop->index }}" value="{{ $m }}"
                                       @checked(old('minat') === $m)>
                                <label class="form-check-label" for="minat-{{ $loop->index }}">{{ $m }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- --------------------------------------------- anggaran --}}
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">2. Anggaran perjalanan</div>
                    <div class="card-body">
                        <p class="text-muted small">Perkiraan total biaya perjalananmu.</p>
                        @foreach ($anggaran as $a)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="anggaran"
                                       id="anggaran-{{ $loop->index }}" value="{{ $a }}"
                                       @checked(old('anggaran') === $a)>
                                <label class="form-check-label" for="anggaran-{{ $loop->index }}">
                                    @if ($a === 'Rendah')
                                        Rendah (&lt; Rp250.000)
                                    @elseif ($a === 'Sedang')
                                        Sedang (Rp250.000 &ndash; 500.000)
                                    @else
                                        Tinggi (&gt; Rp500.000)
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ------------------------------------------------ jarak --}}
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">3. Jarak tempuh</div>
                    <div class="card-body">
                        <p class="text-muted small">Jarak maksimal dari pusat Kota Bandar Lampung.</p>
                        @foreach ($jarak as $j)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="jarak"
                                       id="jarak-{{ $loop->index }}" value="{{ $j }}"
                                       @checked(old('jarak') === $j)>
                                <label class="form-check-label" for="jarak-{{ $loop->index }}">
                                    @if ($j === 'Dekat')
                                        Dekat (&lt; 15 km)
                                    @elseif ($j === 'Sedang')
                                        Sedang (15 &ndash; 30 km)
                                    @else
                                        Jauh (&gt; 30 km)
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-lg px-5 text-white" style="background-color:var(--laut)">
                Dapatkan Rekomendasi
            </button>
        </div>
    </form>

    <div class="card mt-5">
        <div class="card-header">Kriteria penilaian</div>
        <div class="card-body">
            <div class="row">
                @foreach ($kriteria as $kode => $nama)
                    <div class="col-md-4 mb-2">
                        <span class="badge bg-secondary">{{ $kode }}</span> {{ $nama }}
                        <span class="text-muted small">
                            ({{ in_array($kode, ['C1', 'C2']) ? 'cost' : 'benefit' }})
                        </span>
                    </div>
                @endforeach
            </div>
            <p class="text-muted small mb-0 mt-2">
                Kriteria <em>cost</em> semakin kecil semakin baik (jarak, biaya), sedangkan
                <em>benefit</em> semakin besar semakin baik.
            </p>
        </div>
    </div>
@endsection
