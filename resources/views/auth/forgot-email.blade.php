@extends('layouts.app-auth')

@section('title', 'Lupa Email')

@section('content')

<div class="login-box">

    <div class="card card-outline card-info shadow">

        <div class="card-header text-center">
            <b>Lupa Email</b>
            <p class="text-muted mb-0">Masukkan data akun Anda</p>
        </div>

        <div class="card-body">

            @include('auth.partials.alerts')

            <form method="POST" action="{{ route('forgot.email.process') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text"
                        name="identitas"
                        class="form-control"
                        placeholder="NOMOR HP / NIK / NIP / NIB"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>
                </div>

                <small class="text-muted">
                    Masukkan salah satu: NIK (16 digit), NIP, NIB, atau NO. HP
                </small>

                <button class="btn btn-info btn-block">
                    <i class="fas fa-search"></i> Cari Email
                </button>
            </form>

            <div class="text-center mt-3">

                <a href="{{ route('login') }}">
                    ← Kembali ke Login
                </a>

                <br>

                <a href="{{ route('forgot.form') }}" class="text-muted">
                    Lupa Password?
                </a>

            </div>

        </div>
    </div>
</div>

@endsection