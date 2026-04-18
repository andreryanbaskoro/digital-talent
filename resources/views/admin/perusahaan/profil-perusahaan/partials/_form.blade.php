@php
$isEdit = isset($profil);
@endphp

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card card-outline card-primary shadow-sm">

            <form action="{{ route('perusahaan.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        {{-- NAMA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan"
                                    class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                    value="{{ old('nama_perusahaan', $profil->nama_perusahaan ?? '') }}"
                                    placeholder="Masukkan nama perusahaan">

                                @error('nama_perusahaan')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- NIB --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIB</label>
                                <input type="text" name="nib"
                                    class="form-control @error('nib') is-invalid @enderror"
                                    value="{{ old('nib', $profil->nib ?? '') }}">
                                @error('nib')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- NPWP --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NPWP</label>
                                <input type="text" name="npwp"
                                    class="form-control @error('npwp') is-invalid @enderror"
                                    value="{{ old('npwp', $profil->npwp ?? '') }}">
                                @error('npwp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- TELEPON --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No Telepon</label>
                                <input type="text" name="nomor_telepon"
                                    class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    value="{{ old('nomor_telepon', $profil->nomor_telepon ?? '') }}">
                                @error('nomor_telepon')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- WEBSITE --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="text" name="website"
                                    class="form-control @error('website') is-invalid @enderror"
                                    value="{{ old('website', $profil->website ?? '') }}">
                                @error('website')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="provinsi"
                                    class="form-control @error('provinsi') is-invalid @enderror"
                                    value="{{ old('provinsi', $profil->provinsi ?? '') }}">
                                @error('provinsi')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- KABUPATEN --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kabupaten</label>
                                <input type="text" name="kab_kota"
                                    class="form-control @error('kab_kota') is-invalid @enderror"
                                    value="{{ old('kab_kota', $profil->kab_kota ?? '') }}">
                                @error('kab_kota')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    rows="3">{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                                @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- LOGO --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Logo</label>

                                <input type="file" name="logo"
                                    class="form-control @error('logo') is-invalid @enderror">

                                @error('logo')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror

                                {{-- tombol lihat logo --}}
                                @if(isset($profil) && $profil->logo)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/'.$profil->logo) }}"
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

                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="form-group mt-3">
                        <label>Deskripsi Perusahaan</label>
                        <textarea name="deskripsi"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="4">{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end">

                    <a href="{{  url('/profil') }}"
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