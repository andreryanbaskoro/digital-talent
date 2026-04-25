@extends('layouts.app-auth')

@section('title', 'Reset Password')

@section('content')

<div class="login-box">

    <div class="card card-outline card-success shadow">

        <div class="card-header text-center">
            <b>Reset Password</b>
            <p class="text-muted mb-0">Buat password baru Anda</p>
        </div>

        <div class="card-body">

            @include('auth.partials.alerts')

            <form method="POST" action="{{ route('reset.process') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mb-3">
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password"
                        class="form-control"
                        placeholder="Password baru"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation"
                        class="form-control"
                        placeholder="Konfirmasi password"
                        required>
                </div>

                <button class="btn btn-success btn-block">
                    <i class="fas fa-check"></i> Reset Password
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">
                    ← Kembali ke Login
                </a>
            </div>

        </div>
    </div>
</div>

@endsection