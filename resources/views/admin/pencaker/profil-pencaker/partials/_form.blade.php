@php
$isEdit = isset($profil);
@endphp

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card card-outline card-primary shadow-sm">

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        {{-- NIK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" name="nik"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik', $profil->nik ?? '') }}">

                                @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- NO KK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No KK</label>
                                <input type="text" name="nomor_kk"
                                    class="form-control @error('nomor_kk') is-invalid @enderror"
                                    value="{{ old('nomor_kk', $profil->nomor_kk ?? '') }}">
                            </div>
                        </div>

                        {{-- NAMA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap"
                                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap', $profil->nama_lengkap ?? '') }}">
                            </div>
                        </div>

                        {{-- TEMPAT LAHIR --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir"
                                    class="form-control"
                                    value="{{ old('tempat_lahir', $profil->tempat_lahir ?? '') }}">
                            </div>
                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>

                                <input type="date"
                                    name="tanggal_lahir"
                                    class="form-control"
                                    value="{{ old('tanggal_lahir', isset($profil->tanggal_lahir) ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- AGAMA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama"
                                    class="form-control"
                                    value="{{ old('agama', $profil->agama ?? '') }}">
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Perkawinan</label>
                                <input type="text" name="status_perkawinan"
                                    class="form-control"
                                    value="{{ old('status_perkawinan', $profil->status_perkawinan ?? '') }}">
                            </div>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat</label>

                                <textarea name="alamat"
                                    class="form-control text-left"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, kelurahan, kecamatan)">{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- RT / RW --}}
                        <div class="col-md-6">
                            <label>RT</label>
                            <input type="text" name="rt" class="form-control"
                                value="{{ old('rt', $profil->rt ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label>RW</label>
                            <input type="text" name="rw" class="form-control"
                                value="{{ old('rw', $profil->rw ?? '') }}">
                        </div>

                        {{-- KELURAHAN --}}
                        <div class="col-md-6">
                            <label>Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control"
                                value="{{ old('kelurahan', $profil->kelurahan ?? '') }}">
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-md-6">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control"
                                value="{{ old('kecamatan', $profil->kecamatan ?? '') }}">
                        </div>

                        {{-- KABUPATEN --}}
                        <div class="col-md-6">
                            <label>Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control"
                                value="{{ old('kabupaten', $profil->kabupaten ?? '') }}">
                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-md-6">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" class="form-control"
                                value="{{ old('provinsi', $profil->provinsi ?? '') }}">
                        </div>

                        {{-- KODE POS --}}
                        <div class="col-md-6">
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control"
                                value="{{ old('kode_pos', $profil->kode_pos ?? '') }}">
                        </div>

                        {{-- HP --}}
                        <div class="col-md-6">
                            <label>No HP</label>
                            <input type="text" name="nomor_hp" class="form-control"
                                value="{{ old('nomor_hp', $profil->nomor_hp ?? '') }}">
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-12">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $profil->email ?? '') }}">
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-12 mt-3">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">

                            @if(isset($profil) && $profil->foto)
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$profil->foto) }}"
                                    target="_blank"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-image mr-1"></i> Lihat Foto
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

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end">

                    <a href="{{ url('/profil') }}"
                        class="btn btn-outline-secondary btn-sm btn-kembali mr-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    <button type="button"
                        class="btn btn-primary btn-submit">
                        <i class="fas fa-save"></i> {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>