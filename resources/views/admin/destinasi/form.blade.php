@extends('layouts.app')

@section('judul', ($destinasi->exists ? 'Ubah' : 'Tambah') . ' Destinasi – Admin')

@push('gaya')
<style>
    .check-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.5rem;
    }
    .check-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        background: #fff;
    }
    .check-item:has(input:checked) {
        border-color: var(--primary);
        background: #f0fdfa;
        font-weight: 600;
    }
    .check-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        accent-color: var(--primary);
    }
    .thumb-preview {
        width: 100px;
        height: 68px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #cbd5e1;
    }
</style>
@endpush

@section('konten')
    <div class="admin-nav-bar">
        <div class="admin-nav-tabs">
            <a href="{{ route('admin.destinasi.index') }}" class="admin-tab-link active">
                <i class="bi bi-geo-alt"></i> Data Destinasi
            </a>
            <a href="{{ route('admin.bobot.index') }}" class="admin-tab-link">
                <i class="bi bi-sliders"></i> Bobot Segmen
            </a>
            <a href="{{ route('admin.aturan.index') }}" class="admin-tab-link">
                <i class="bi bi-funnel"></i> Aturan Kelayakan
            </a>
            <a href="{{ route('admin.analisis.index') }}" class="admin-tab-link">
                <i class="bi bi-bar-chart-line"></i> Analisis Sebaran
            </a>
        </div>
        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-dark btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <h1 class="h4 fw-bold mb-3" style="font-family: 'Poppins', sans-serif;">{{ $destinasi->exists ? 'Ubah' : 'Tambah' }} Destinasi</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $destinasi->exists ? route('admin.destinasi.update', $destinasi) : route('admin.destinasi.store') }}">
        @csrf
        @if ($destinasi->exists)
            @method('PUT')
        @endif

        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="bi bi-info-circle me-2"></i>Identitas Destinasi
            </div>
            <div class="card-body row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Kode</label>
                    <input type="text" name="kode" class="form-control font-mono"
                           value="{{ old('kode', $destinasi->kode) }}" required placeholder="A1">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Nama Destinasi</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $destinasi->nama) }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Upload Foto Utama (Lokal)</label>
                    <input type="file" name="foto_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Format JPG, PNG, atau WEBP (maks. 5 MB).</div>
                    @if (!empty($destinasi->foto))
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <img src="{{ $destinasi->foto }}" alt="Foto utama" class="thumb-preview">
                            <span class="text-muted small">Foto utama saat ini (kosongkan jika tidak diganti)</span>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="bi bi-clipboard-data me-2"></i>Skor Kriteria C1, C2, C4, C5
            </div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">C1 Jarak (km)</label>
                    <input type="number" step="0.01" name="c1_jarak" class="form-control font-mono"
                           value="{{ old('c1_jarak', $destinasi->c1_jarak) }}" required min="0.1">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">C2 Biaya (ribu rupiah)</label>
                    <input type="number" step="0.01" name="c2_biaya" class="form-control font-mono"
                           value="{{ old('c2_biaya', $destinasi->c2_biaya) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">C4 Keindahan Alam (1–5)</label>
                    <select name="c4_keindahan" class="form-select font-mono" required>
                        @foreach (range(1, 5) as $n)
                            <option value="{{ $n }}" @selected((int) old('c4_keindahan', $destinasi->c4_keindahan) === $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">C5 Kebersihan Pantai (1–5)</label>
                    <select name="c5_kebersihan" class="form-select font-mono" required>
                        @foreach (range(1, 5) as $n)
                            <option value="{{ $n }}" @selected((int) old('c5_kebersihan', $destinasi->c5_kebersihan) === $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @php
            $daftarFasilitas = [
                'Area Parkir', 'Tempat Istirahat', 'Toilet Umum', 'Tempat Makan & Minum',
                'Tempat Ibadah', 'Area Foto', 'Wifi', 'Wahana/Rekreasi',
                'Area Bermain Anak', 'Penginapan/Homestay', 'Toko/Warung', 'Pos Keamanan',
            ];
            $fasilitasTerpilih = old('fasilitas_list', $destinasi->fasilitas_list ?? []);
        @endphp
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="bi bi-building me-2"></i>C3 Kelengkapan Fasilitas
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Centang fasilitas yang tersedia di destinasi ini. Skor C3 dihitung otomatis:
                    0–2 fasilitas = 1 &bull; 3–4 = 2 &bull; 5–7 = 3 &bull; 8–10 = 4 &bull; 11–12 = 5.
                </p>
                <div class="check-grid">
                    @foreach ($daftarFasilitas as $fas)
                        <label class="check-item">
                            <input type="checkbox" name="fasilitas_list[]" value="{{ $fas }}"
                                   @checked(in_array($fas, (array) $fasilitasTerpilih))>
                            {{ $fas }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        @php
            $daftarAktivitas = ['Snorkeling', 'Diving', 'Wisata Pantai', 'Wisata Pulau'];
            $aktivitasTerpilih = old('aktivitas_list', $destinasi->aktivitas_list ?? []);
            $adaAktivitas = function (string $nama) use ($aktivitasTerpilih): bool {
                foreach ((array) $aktivitasTerpilih as $item) {
                    if (str_starts_with($item, $nama)) return true;
                }
                return false;
            };
        @endphp
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="bi bi-activity me-2"></i>C6 Keragaman Kesesuaian Aktivitas
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Centang aktivitas yang tersedia di destinasi ini.
                    Setiap aktivitas dievaluasi terpisah sesuai minat yang dipilih wisatawan (bernilai skor <strong>5</strong> jika tersedia dan <strong>1</strong> jika tidak tersedia, tidak dijumlahkan).
                </p>
                <div class="check-grid">
                    @foreach ($daftarAktivitas as $akt)
                        <label class="check-item">
                            <input type="checkbox" name="aktivitas_list[]" value="{{ $akt }}"
                                   @checked($adaAktivitas($akt))>
                            {{ $akt }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="bi bi-images me-2"></i>Galeri Foto Destinasi (Upload File Lokal)
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Pilih file foto dari perangkat Anda (maksimal 3 foto, format JPG/PNG/WEBP). Foto akan disimpan di server lokal dan ditampilkan pada galeri destinasi.
                </p>
                <div class="row g-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Foto Galeri {{ $i + 1 }}</label>
                            <input type="file" name="foto_galeri[{{ $i }}]" class="form-control" accept="image/jpeg,image/png,image/webp">
                            @if (!empty($destinasi->foto_list[$i]))
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <img src="{{ $destinasi->foto_list[$i] }}" alt="Foto {{ $i + 1 }}" class="thumb-preview">
                                    <span class="text-muted small">Tersimpan</span>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <button class="btn-marine" type="submit">
            <i class="bi bi-check-lg"></i>
            {{ $destinasi->exists ? 'Simpan Perubahan' : 'Tambah Destinasi' }}
        </button>
        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
    </form>
@endsection
