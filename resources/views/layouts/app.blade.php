<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'SPK Wisata Bahari Pesawaran')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --laut: #0b4f6c; --pasir: #f6f1e7; }
        body { background-color: #f7f9fb; }
        .navbar { background-color: var(--laut); }
        .navbar-brand, .nav-link { color: #fff !important; }
        .navbar-brand span { color: #ffd166; }
        .hero {
            background: linear-gradient(135deg, #0b4f6c 0%, #1b7f9e 100%);
            color: #fff; border-radius: 1rem; padding: 2.5rem;
        }
        .card { border: none; box-shadow: 0 2px 12px rgba(11,79,108,.08); border-radius: .8rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid #eef2f5; font-weight: 600; }
        .badge-peringkat { background-color: var(--laut); }
        .table > :not(caption) > * > * { padding: .6rem .75rem; }
        footer { color: #6c757d; font-size: .875rem; }
        .nilai-v { font-variant-numeric: tabular-nums; font-weight: 600; }
    </style>
    @stack('gaya')
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('beranda') }}">SPK <span>Wisata Bahari</span> Pesawaran</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navUtama">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navUtama">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('beranda') }}">Beranda</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.destinasi.index') }}">Admin</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">Keluar</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.login') }}">Login Admin</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @if (session('sukses'))
        <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif
    @if (session('galat'))
        <div class="alert alert-warning">{{ session('galat') }}</div>
    @endif

    @yield('konten')
</main>

<footer class="container py-4 border-top">
    <p class="mb-0">
        Sistem Pendukung Keputusan Pemilihan Destinasi Wisata Bahari Kabupaten Pesawaran
        &mdash; Metode Simple Additive Weighting.
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('skrip')
</body>
</html>
