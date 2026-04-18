@php
$isEdit = isset($perusahaan);
@endphp

<div class="card card-primary">
    <form action="{{ $isEdit ? route('disnaker.perusahaan.update', $perusahaan->id_perusahaan) : '#' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            <div class="row">

                {{-- ID Perusahaan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID Perusahaan</label>
                        <input type="text" class="form-control"
                            value="{{ $isEdit ? $perusahaan->id_perusahaan : 'Auto Generate' }}"
                            readonly>
                    </div>
                </div>

                {{-- ID Pengguna --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_pengguna">ID Pengguna</label>
                        <input type="text" name="id_pengguna" id="id_pengguna"
                            class="form-control @error('id_pengguna') is-invalid @enderror"
                            value="{{ old('id_pengguna', $isEdit ? $perusahaan->id_pengguna : '') }}"
                            placeholder="Contoh: USR-001">
                        @error('id_pengguna')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Nama Perusahaan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_perusahaan">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                            class="form-control @error('nama_perusahaan') is-invalid @enderror"
                            value="{{ old('nama_perusahaan', $isEdit ? $perusahaan->nama_perusahaan : '') }}"
                            placeholder="Contoh: PT Maju Jaya Sejahtera">
                        @error('nama_perusahaan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- NIB --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nib">NIB</label>
                        <input type="text" name="nib" id="nib"
                            class="form-control @error('nib') is-invalid @enderror"
                            value="{{ old('nib', $isEdit ? $perusahaan->nib : '') }}">
                        @error('nib')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- NPWP --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npwp">NPWP</label>
                        <input type="text" name="npwp" id="npwp"
                            class="form-control @error('npwp') is-invalid @enderror"
                            value="{{ old('npwp', $isEdit ? $perusahaan->npwp : '') }}">
                        @error('npwp')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" id="nomor_telepon"
                            class="form-control @error('nomor_telepon') is-invalid @enderror"
                            value="{{ old('nomor_telepon', $isEdit ? $perusahaan->nomor_telepon : '') }}">
                        @error('nomor_telepon')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Website --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website"
                            class="form-control @error('website') is-invalid @enderror"
                            value="{{ old('website', $isEdit ? $perusahaan->website : '') }}"
                            placeholder="https://example.com">
                        @error('website')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kabupaten --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kab_kota">Kabupaten / Kota</label>
                        <input type="text" name="kab_kota" id="kab_kota"
                            class="form-control @error('kab_kota') is-invalid @enderror"
                            value="{{ old('kab_kota', $isEdit ? $perusahaan->kab_kota : '') }}">
                        @error('kab_kota')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Provinsi --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi"
                            class="form-control @error('provinsi') is-invalid @enderror"
                            value="{{ old('provinsi', $isEdit ? $perusahaan->provinsi : '') }}">
                        @error('provinsi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat"
                            class="form-control @error('alamat') is-invalid @enderror"
                            rows="3">{{ old('alamat', $isEdit ? $perusahaan->alamat : '') }}</textarea>
                        @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Logo --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="logo">Logo Perusahaan</label>

                        <input type="file" name="logo" id="logo"
                            class="form-control @error('logo') is-invalid @enderror">

                        @error('logo')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror

                        {{-- preview logo lama --}}
                        @if($isEdit && $perusahaan->logo)
                        <div class="mt-2">
                            <a href="{{ asset('storage/'.$perusahaan->logo) }}"
                                target="_blank"
                                class="btn btn-outline-info btn-sm">
                                <i class="fas fa-image"></i> Lihat Logo
                            </a>
                        </div>
                        @else
                        <small class="text-muted d-block mt-2">
                            Belum ada logo
                        </small>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="4">{{ old('deskripsi', $isEdit ? $perusahaan->deskripsi : '') }}</textarea>
                        @error('deskripsi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">

            <a href="route('disnaker.perusahaan.index') }}" class="btn btn-outline-secondary btn-kembali btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <button type="button" class="btn btn-primary btn-submit btn-sm">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>

        </div>
    </form>
</div>