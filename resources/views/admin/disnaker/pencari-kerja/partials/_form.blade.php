@php
$isEdit = isset($pencariKerja);
@endphp

<div class="card card-primary">
    <form action="{{ $isEdit ? route('pencari_kerja.update', $pencariKerja->id_pencari_kerja) : route('pencari_kerja.store') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            <div class="row">

                {{-- ID --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID Pencari Kerja</label>
                        <input type="text" class="form-control"
                            value="{{ $isEdit ? $pencariKerja->id_pencari_kerja : ($previewId ?? '-') }}"
                            readonly>
                    </div>
                </div>

                {{-- Nama --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap"
                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                            value="{{ old('nama_lengkap', $isEdit ? $pencariKerja->nama_lengkap : '') }}"
                            required>
                        @error('nama_lengkap')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- NIK --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>NIK *</label>
                        <input type="text" name="nik"
                            maxlength="16"
                            class="form-control @error('nik') is-invalid @enderror"
                            value="{{ old('nik', $isEdit ? $pencariKerja->nik : '') }}"
                            required>
                        @error('nik')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Nomor KK --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor KK</label>
                        <input type="text" name="nomor_kk"
                            class="form-control"
                            value="{{ old('nomor_kk', $isEdit ? $pencariKerja->nomor_kk : '') }}">
                    </div>
                </div>

                {{-- Tempat Lahir --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            class="form-control"
                            value="{{ old('tempat_lahir', $isEdit ? $pencariKerja->tempat_lahir : '') }}">
                    </div>
                </div>

                {{-- Tanggal Lahir --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            class="form-control"
                            value="{{ old('tanggal_lahir', $isEdit && $pencariKerja->tanggal_lahir ? $pencariKerja->tanggal_lahir->format('Y-m-d') : '') }}">
                    </div>
                </div>

                {{-- Status Perkawinan --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status Perkawinan</label>
                        <select name="status_perkawinan" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="Belum Kawin" {{ old('status_perkawinan', $isEdit ? $pencariKerja->status_perkawinan : '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="Kawin" {{ old('status_perkawinan', $isEdit ? $pencariKerja->status_perkawinan : '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                            <option value="Cerai" {{ old('status_perkawinan', $isEdit ? $pencariKerja->status_perkawinan : '') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        </select>
                    </div>
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $isEdit ? $pencariKerja->jenis_kelamin : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $isEdit ? $pencariKerja->jenis_kelamin : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- Agama --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Agama</label>
                        <input type="text" name="agama"
                            class="form-control"
                            value="{{ old('agama', $isEdit ? $pencariKerja->agama : '') }}">
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ old('alamat', $isEdit ? $pencariKerja->alamat : '') }}</textarea>
                    </div>
                </div>

                {{-- RT RW --}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label>RT</label>
                        <input type="text" name="rt" class="form-control"
                            value="{{ old('rt', $isEdit ? $pencariKerja->rt : '') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>RW</label>
                        <input type="text" name="rw" class="form-control"
                            value="{{ old('rw', $isEdit ? $pencariKerja->rw : '') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kelurahan</label>
                        <input type="text" name="kelurahan" class="form-control"
                            value="{{ old('kelurahan', $isEdit ? $pencariKerja->kelurahan : '') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control"
                            value="{{ old('kecamatan', $isEdit ? $pencariKerja->kecamatan : '') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" name="kabupaten" class="form-control"
                            value="{{ old('kabupaten', $isEdit ? $pencariKerja->kabupaten : '') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control"
                            value="{{ old('provinsi', $isEdit ? $pencariKerja->provinsi : '') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control"
                            value="{{ old('kode_pos', $isEdit ? $pencariKerja->kode_pos : '') }}">
                    </div>
                </div>

                {{-- HP --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor HP *</label>
                        <input type="text" name="nomor_hp"
                            class="form-control @error('nomor_hp') is-invalid @enderror"
                            value="{{ old('nomor_hp', $isEdit ? $pencariKerja->nomor_hp : '') }}"
                            required>
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $isEdit ? $pencariKerja->email : '') }}"
                            required>
                    </div>
                </div>

                {{-- FOTO --}}
                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control">

                        @if(isset($pencariKerja) && $pencariKerja->foto)
                        <div class="mt-2">
                            <a href="{{ asset('storage/'.$pencariKerja->foto) }}"
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

        <div class="card-footer text-right">
            <a href="{{ route('pencari_kerja.index') }}" class="btn btn-kembali btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <button type="button" class="btn btn-submit btn-primary btn-sm">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>