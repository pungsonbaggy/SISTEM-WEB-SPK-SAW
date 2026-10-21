@extends('layouts.app')

@section('judul', 'Login Admin')

@section('konten')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Login Administrator</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button class="btn text-white w-100" style="background-color:var(--laut)" type="submit">
                            Masuk
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-muted small mb-0">
                        Akun bawaan: <code>admin@spk.test</code> / <code>admin12345</code>.
                        Ganti password sebelum aplikasi dipakai orang lain.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
