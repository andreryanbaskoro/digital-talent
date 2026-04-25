@extends('layouts.app-auth')

@section('title', 'Lupa Password')

@section('content')

<div class="login-box">

    <div class="card card-outline card-primary shadow">

        <div class="card-header text-center">
            <b>Lupa Password</b>
            <p class="text-muted mb-0">Masukkan email untuk reset password</p>
        </div>

        <div class="card-body">

            @include('auth.partials.alerts')

            <form method="POST" action="{{ route('forgot.process') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Masukkan email Anda"
                        value="{{ old('email') }}"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Kirim Link Reset
                </button>
            </form>

            <div class="text-center mt-3">

                <a href="{{ route('login') }}" class="text-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>

                <br>

                <a href="{{ route('register') }}" class="text-muted">
                    Belum punya akun? Daftar
                </a>

            </div>

        </div>
    </div>
</div>

@endsection