@extends('layouts.app-auth')

@section('title', 'Sign Up')

@section('content')

<div class="register-box">
    <div class="card card-outline card-primary shadow-sm">

        {{-- HEADER --}}
        <div class="card-header text-center">
            <h4 class="text-primary font-weight-bold mb-0">Digital Talent Hub</h4>
            <small class="text-muted">Buat akun baru</small>
        </div>

        <div class="card-body">

            @include('auth.partials.alerts')

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                {{-- ================= AKUN ================= --}}
                <h6 class="text-muted font-weight-bold mb-2">
                    <i class="fas fa-user-circle"></i> Informasi Akun
                </h6>

                {{-- Nama --}}
                <div class="input-group mb-1">
                    <input type="text" name="nama"
                        value="{{ old('nama') }}"
                        class="form-control @error('nama') is-invalid @enderror"
                        placeholder="Nama akun">
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                    </div>
                </div>
                <small class="text-muted">Nama yang digunakan untuk login</small>
                @error('nama') <small class="text-danger d-block">{{ $message }}</small> @enderror

                {{-- Email --}}
                <div class="input-group mt-2 mb-1">
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email aktif">
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <small class="text-muted">Gunakan email yang aktif</small>
                @error('email') <small class="text-danger d-block">{{ $message }}</small> @enderror

                {{-- Role --}}
                <div class="input-group mt-2 mb-1">
                    <select name="peran" id="peran"
                        class="form-control @error('peran') is-invalid @enderror">
                        <option value="">-- Pilih Peran --</option>
                        <option value="pencaker" {{ old('peran')=='pencaker'?'selected':'' }}>Pencari Kerja</option>
                        <option value="perusahaan" {{ old('peran')=='perusahaan'?'selected':'' }}>Perusahaan</option>
                        <option value="disnaker" {{ old('peran')=='disnaker'?'selected':'' }}>Disnaker</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <small class="text-muted">Pilih jenis akun</small>
                @error('peran') <small class="text-danger d-block">{{ $message }}</small> @enderror

                {{-- ================= DISNAKER ================= --}}
                <div id="disnaker-form" class="border rounded p-3 mt-3 bg-light" style="display:none;">
                    <h6 class="text-primary font-weight-bold">
                        <i class="fas fa-id-badge"></i> Data Disnaker
                    </h6>

                    <div class="input-group mb-1">
                        <input type="text" name="nip"
                            value="{{ old('nip') }}"
                            class="form-control @error('nip') is-invalid @enderror"
                            placeholder="NIP">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                        </div>
                    </div>
                    <small class="text-muted">Nomor Induk Pegawai</small>
                    @error('nip') <small class="text-danger d-block">{{ $message }}</small> @enderror
                </div>

                {{-- ================= PENCAKER ================= --}}
                <div id="pencaker-form" class="border rounded p-3 mt-3 bg-light" style="display:none;">
                    <h6 class="text-success font-weight-bold">
                        <i class="fas fa-user-tie"></i> Profil Pencari Kerja
                    </h6>

                    <div class="input-group mb-1">
                        <input type="text" name="nik"
                            value="{{ old('nik') }}"
                            class="form-control @error('nik') is-invalid @enderror"
                            placeholder="NIK">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                        </div>
                    </div>
                    <small class="text-muted">16 digit sesuai KTP</small>
                    @error('nik') <small class="text-danger d-block">{{ $message }}</small> @enderror

                    <div class="input-group mt-2 mb-1">
                        <input type="text" name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            class="form-control"
                            placeholder="Nama lengkap">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                        </div>
                    </div>

                    <div class="input-group mt-2 mb-1">
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir') }}"
                            class="form-control @error('tanggal_lahir') is-invalid @enderror">
                    </div>
                    <small class="text-muted">Tanggal lahir sesuai KTP</small>

                    <div class="input-group mt-2 mb-1">
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="input-group mt-2 mb-1">
                        <input type="text" name="nomor_hp"
                            value="{{ old('nomor_hp') }}"
                            class="form-control @error('nomor_hp') is-invalid @enderror"
                            placeholder="Nomor HP">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                        </div>
                    </div>
                    <small class="text-muted">Contoh: 08123456789</small>

                    <textarea name="alamat"
                        class="form-control mt-2 @error('alamat') is-invalid @enderror"
                        placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                </div>

                {{-- ================= PERUSAHAAN ================= --}}
                <div id="perusahaan-form" class="border rounded p-3 mt-3 bg-light" style="display:none;">
                    <h6 class="text-warning font-weight-bold">
                        <i class="fas fa-building"></i> Profil Perusahaan
                    </h6>

                    <div class="input-group mb-1">
                        <input type="text" name="nama_perusahaan"
                            value="{{ old('nama_perusahaan') }}"
                            class="form-control"
                            placeholder="Nama perusahaan">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-building"></i></div>
                        </div>
                    </div>

                    <div class="input-group mt-2 mb-1">
                        <input type="text" name="nib"
                            value="{{ old('nib') }}"
                            class="form-control"
                            placeholder="NIB">
                    </div>
                    <small class="text-muted">Nomor Induk Berusaha</small>

                    <div class="input-group mt-2 mb-1">
                        <input type="text" name="npwp"
                            value="{{ old('npwp') }}"
                            class="form-control"
                            placeholder="NPWP">
                    </div>

                    <div class="input-group mt-2 mb-1">
                        <input type="text" name="nomor_telepon"
                            value="{{ old('nomor_telepon') }}"
                            class="form-control"
                            placeholder="No Telepon">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                        </div>
                    </div>

                    <div class="input-group mt-2">
                        <input type="url" name="website"
                            value="{{ old('website') }}"
                            class="form-control"
                            placeholder="Website (opsional)">
                    </div>
                </div>

                {{-- ================= PASSWORD ================= --}}
                <h6 class="text-muted font-weight-bold mt-3">
                    <i class="fas fa-lock"></i> Keamanan
                </h6>

                <div class="input-group mb-1">
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <small class="text-muted">Minimal 8 karakter</small>

                <div class="input-group mt-2 mb-3">
                    <input type="password" name="password_confirmation"
                        class="form-control"
                        placeholder="Konfirmasi Password">
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-check"></i></div>
                    </div>
                </div>

                <button class="btn btn-primary btn-block font-weight-bold">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>

            </form>

            <div class="text-center mt-3">
                <small>
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-weight-bold text-primary">Login</a>
                </small>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const peran = document.getElementById('peran');
        const disnaker = document.getElementById('disnaker-form');
        const pencaker = document.getElementById('pencaker-form');
        const perusahaan = document.getElementById('perusahaan-form');

        function toggleForm() {
            disnaker.style.display = 'none';
            pencaker.style.display = 'none';
            perusahaan.style.display = 'none';

            if (peran.value === 'disnaker') disnaker.style.display = 'block';
            if (peran.value === 'pencaker') pencaker.style.display = 'block';
            if (peran.value === 'perusahaan') perusahaan.style.display = 'block';
        }

        peran.addEventListener('change', toggleForm);
        toggleForm();

        // auto scroll ke error
        const error = document.querySelector('.is-invalid');
        if (error) error.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

    });
</script>
@endpush