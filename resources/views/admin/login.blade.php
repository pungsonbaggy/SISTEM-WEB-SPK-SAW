@extends('layouts.app')

@section('judul', 'Login Admin')

@section('konten')
    <div class="row justify-content-center my-4">
        <div class="col-md-5 col-lg-4">
            <div class="card card-custom p-2">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="h4 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Masuk Administrator</h1>
                        <p class="text-muted small mb-0">Panel Pengelolaan SPK Wisata Bahari Pesawaran</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 d-flex align-items-center small">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" required autofocus
                                   placeholder="admin@spk.test">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary" for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                                   name="password" required placeholder="••••••••">
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat sesi saya</label>
                        </div>
                        <button class="btn-marine w-100" type="submit">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk ke Panel Admin
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-muted small mb-0 text-center">
                        Akun bawaan: <code>admin@spk.test</code> / <code>admin12345</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
