@php
$isEdit = isset($profil);
@endphp

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card card-outline card-primary shadow-sm">

            <form action="{{ route('pencaker.profil.update') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">

                        {{-- NIK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIK *</label>
                                <input type="text"
                                    name="nik"
                                    maxlength="16"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik', $profil->nik ?? '') }}"
                                    required>
                                @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- NO KK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor KK</label>
                                <input type="text"
                                    name="nomor_kk"
                                    class="form-control"
                                    value="{{ old('nomor_kk', $profil->nomor_kk ?? '') }}">
                            </div>
                        </div>

                        {{-- NAMA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text"
                                    name="nama_lengkap"
                                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap', $profil->nama_lengkap ?? '') }}"
                                    required>
                                @error('nama_lengkap')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- TEMPAT LAHIR --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text"
                                    name="tempat_lahir"
                                    class="form-control"
                                    value="{{ old('tempat_lahir', $profil->tempat_lahir ?? '') }}">
                            </div>
                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date"
                                    name="tanggal_lahir"
                                    class="form-control"
                                    value="{{ old('tanggal_lahir', isset($profil->tanggal_lahir) ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        {{-- STATUS PERKAWINAN --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Perkawinan</label>
                                <select name="status_perkawinan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Belum Kawin" {{ old('status_perkawinan', $profil->status_perkawinan ?? '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="Kawin" {{ old('status_perkawinan', $profil->status_perkawinan ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="Cerai" {{ old('status_perkawinan', $profil->status_perkawinan ?? '') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                </select>
                            </div>
                        </div>

                        {{-- AGAMA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text"
                                    name="agama"
                                    class="form-control"
                                    value="{{ old('agama', $profil->agama ?? '') }}">
                            </div>
                        </div>

                        {{-- NO HP --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP *</label>
                                <input type="text"
                                    name="nomor_hp"
                                    class="form-control @error('nomor_hp') is-invalid @enderror"
                                    value="{{ old('nomor_hp', $profil->nomor_hp ?? '') }}"
                                    required>
                                @error('nomor_hp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $profil->email ?? '') }}"
                                    required>
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <textarea name="alamat"
                                    rows="3"
                                    class="form-control">{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- DETAIL WILAYAH --}}
                        <div class="col-md-2">
                            <label>RT</label>
                            <input type="text" name="rt" class="form-control"
                                value="{{ old('rt', $profil->rt ?? '') }}">
                        </div>

                        <div class="col-md-2">
                            <label>RW</label>
                            <input type="text" name="rw" class="form-control"
                                value="{{ old('rw', $profil->rw ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control"
                                value="{{ old('kelurahan', $profil->kelurahan ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control"
                                value="{{ old('kecamatan', $profil->kecamatan ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label>Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control"
                                value="{{ old('kabupaten', $profil->kabupaten ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" class="form-control"
                                value="{{ old('provinsi', $profil->provinsi ?? '') }}">
                        </div>

                        <div class="col-md-2">
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control"
                                value="{{ old('kode_pos', $profil->kode_pos ?? '') }}">
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label>Foto</label>
                                <input type="file" name="foto" class="form-control">

                                @if(isset($profil) && $profil->foto)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/'.$profil->foto) }}"
                                        target="_blank"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-image"></i> Lihat Foto
                                    </a>
                                </div>
                                @else
                                <small class="text-muted d-block mt-2">
                                    Belum ada foto
                                </small>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-right">
                    <a href="{{ route('pencaker.profil.index') }}"
                        class="btn btn-secondary btn-kembali btn-sm mr-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    <button type="button"
                        class="btn btn-submit btn-primary btn-sm">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>