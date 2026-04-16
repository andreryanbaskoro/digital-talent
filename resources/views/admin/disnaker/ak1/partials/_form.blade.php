@php
$isEdit = isset($pengguna);
@endphp

<div class="card card-primary">
    <form action="{{ $isEdit ? route('pengguna.update', $pengguna->id_pengguna) : route('pengguna.store') }}" method="POST">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            <div class="row">

                {{-- ID Pengguna --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_pengguna">ID Pengguna</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $isEdit ? $pengguna->id_pengguna : $previewId }}"
                            readonly>
                    </div>
                </div>

                {{-- Nama --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $isEdit ? $pengguna->nama : '') }}"
                            placeholder="Contoh: Budi Santoso">
                        @error('nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $isEdit ? $pengguna->email : '') }}"
                            placeholder="Contoh: budi@email.com">
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kata Sandi --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kata_sandi">{{ $isEdit ? 'Ubah Kata Sandi' : 'Kata Sandi' }}</label>
                        <input type="password" name="kata_sandi" id="kata_sandi"
                            class="form-control @error('kata_sandi') is-invalid @enderror"
                            placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah kata sandi' : 'Minimal 8 karakter' }}">
                        @error('kata_sandi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Peran --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="peran">Peran</label>
                        <select name="peran" id="peran"
                            class="form-control @error('peran') is-invalid @enderror">
                            <option value="">-- Pilih Peran Pengguna --</option>
                            @foreach(['disnaker','perusahaan','pencaker'] as $role)
                            <option value="{{ $role }}"
                                {{ old('peran', $isEdit ? $pengguna->peran : '') == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $role)) }}
                            </option>
                            @endforeach
                        </select>
                        @error('peran')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status"
                            class="form-control @error('status') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            @foreach(['aktif','nonaktif'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $isEdit ? $pengguna->status : '') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">

            {{-- Kembali --}}
            <a href="{{ route('disnaker.pengguna.index') }}"
                class="btn btn-outline-secondary btn-sm mr-2 btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- Simpan / Update --}}
            <button type="submit"
                class="btn btn-primary btn-submit btn-sm">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>

        </div>
    </form>
</div>