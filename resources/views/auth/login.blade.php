@extends('layouts.app-auth')

@section('title', 'Login - Digital Talent Hub')

@section('content')

<div class="login-box">
    <div class="card card-outline card-primary shadow-sm">

        <div class="card-header text-center">
            <a href="{{ route('landing') }}" class="h4 font-weight-bold text-primary">
                Digital Talent Hub
            </a>
        </div>

        <div class="card-body">

            {{-- ALERT --}}
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('login') }}" method="POST">
                @csrf

                {{-- EMAIL --}}
                <div class="input-group mb-3">
                    <input type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required autofocus>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    @error('email')
                    <span class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="input-group mb-3">
                    <input type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                    <span class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="row">
                    <div class="col-12 mb-2">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                        </button>
                    </div>
                </div>
            </form>

            {{-- LINK TAMBAHAN --}}
            <div class="text-center mt-3">

                <p class="mb-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-weight-bold text-primary">
                        Daftar sekarang
                    </a>
                </p>

                <a href="{{ route('forgot.form') }}" class="d-block text-muted mb-1">
                    Lupa password?
                </a>

                <a href="{{ route('forgot.email.form') }}" class="d-block text-muted mb-2">
                    Lupa email?
                </a>

                <a href="{{ route('landing') }}" class="d-block text-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Landing
                </a>

            </div>

        </div>
    </div>
</div>

@endsection