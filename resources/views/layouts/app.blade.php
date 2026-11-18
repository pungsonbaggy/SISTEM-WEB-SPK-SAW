<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'SPK Wisata Bahari Pesawaran')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Poppins:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0a2e36;
            --primary-marine: #0a2e36;
            --primary-dark: #061c21;
            --accent: #0284c7;
            --ink: #0f172a;
            --ink-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-canvas: #f8fafc;
        }

        body {
            font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-size: 0.96rem;
            line-height: 1.55;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .navbar-custom {
            background: var(--primary-dark);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.75rem 0;
        }

        .navbar-brand-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #ffffff !important;
            font-size: 1.18rem;
            line-height: 1.2;
            display: block;
        }

        .navbar-brand-subtitle {
            color: #94a3b8;
            font-weight: 400;
            font-size: 0.78rem;
            display: block;
            margin-top: 1px;
        }

        .nav-pill-group {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 0.22rem;
            gap: 0.2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.78) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.42rem 0.9rem !important;
            border-radius: 7px;
            transition: all 0.15s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.07);
        }

        .nav-link.active {
            color: #ffffff !important;
            background: rgba(14, 165, 233, 0.22);
            font-weight: 600;
        }

        .btn-nav-auth {
            font-size: 0.84rem;
            font-weight: 600;
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            padding: 0.42rem 0.9rem;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-nav-auth:hover {
            background: #ffffff;
            color: var(--primary-dark);
            border-color: #ffffff;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        }

        .card-header-custom {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.35rem;
            font-weight: 700;
            color: var(--ink);
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--primary);
            background: #e6f4f1;
            border: 1px solid #b5dfd6;
            padding: 0.22rem 0.7rem;
            border-radius: 6px;
        }

        .option-card-label {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1.05rem;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
            background: #ffffff;
            user-select: none;
        }

        .option-card-label:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .form-check-input:checked + .option-card-label {
            border-color: var(--primary);
            background: #f0fdfa;
            box-shadow: 0 0 0 1px var(--primary);
        }

        .option-card-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .form-check-input:checked + .option-card-label .option-card-icon {
            background: var(--primary);
            color: #ffffff;
        }

        .option-check-indicator {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1.5px solid #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: all 0.15s ease;
            background: #ffffff;
        }

        .form-check-input:checked + .option-card-label .option-check-indicator {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .btn-marine {
            font-family: 'Poppins', sans-serif;
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.92rem;
            border: 1px solid var(--primary);
            padding: 0.65rem 1.4rem;
            border-radius: 9px;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            text-decoration: none;
        }

        .btn-marine:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #ffffff;
        }

        .admin-nav-bar {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.45rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .admin-nav-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .admin-tab-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.48rem 0.95rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.87rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .admin-tab-link:hover {
            background: #f1f5f9;
            color: var(--ink);
        }

        .admin-tab-link.active {
            background: var(--primary);
            color: #ffffff;
        }

        .table-custom th {
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }

        .table-custom td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            color: var(--ink-muted);
            font-size: 0.85rem;
        }
    </style>
    @stack('gaya')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand text-decoration-none py-0" href="{{ route('beranda') }}">
            <span class="navbar-brand-title">PesawaranBahari</span>
            <span class="navbar-brand-subtitle">Pemilihan Destinasi Wisata Bahari</span>
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navUtama">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navUtama">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 mt-3 mt-lg-0">
                <div class="d-flex flex-column flex-lg-row nav-pill-group">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('beranda') || request()->routeIs('rekomendasi.*') || (request()->routeIs('destinasi.show') && session()->has('rekomendasi')) ? 'active' : '' }}" href="{{ route('beranda') }}">
                            <i class="bi bi-compass me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('destinasi.index') || (request()->routeIs('destinasi.show') && !session()->has('rekomendasi')) ? 'active' : '' }}" href="{{ route('destinasi.index') }}">
                            <i class="bi bi-grid-1x2 me-1"></i> Destinasi
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.destinasi.index') }}">
                                <i class="bi bi-sliders2-vertical me-1"></i> Admin Panel
                            </a>
                        </li>
                    @endauth
                </div>
                @auth
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="btn-nav-auth w-100" type="submit">
                                <i class="bi bi-box-arrow-right me-1"></i> Keluar
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <a class="btn-nav-auth d-inline-block" href="{{ route('admin.login') }}">
                            <i class="bi bi-shield-lock me-1"></i> Login Admin
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    @if (session('sukses'))
        <div class="alert alert-success alert-dismissible fade show border-0 border-start border-success border-4 mb-4 card-custom" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <div>{{ session('sukses') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('galat'))
        <div class="alert alert-warning alert-dismissible fade show border-0 border-start border-warning border-4 mb-4 card-custom" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-5 me-2"></i>
                <div>{{ session('galat') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('konten')
</main>

<footer class="py-4">
    <div class="container text-center text-md-start">
        <strong class="text-dark">Sistem Pendukung Keputusan Pemilihan Destinasi Wisata Bahari</strong><br>
        <span class="text-muted small">Kabupaten Pesawaran &bull; Muhammad Fasya Atthoriq (122140107) &bull; Teknik Informatika ITERA</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('skrip')
</body>
</html>
