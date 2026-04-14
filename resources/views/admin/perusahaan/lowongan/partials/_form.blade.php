@php
$isEdit = isset($lowongan);
@endphp

<div class="card card-primary">
    <form action="{{ $isEdit 
        ? route('perusahaan.lowongan.update', $lowongan->id_lowongan) 
        : route('perusahaan.lowongan.store') }}" method="POST">

        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            <div class="row">

                {{-- ID Lowongan --}}
                @if(!$isEdit)
                <div class="col-md-12">
                    <div class="form-group">
                        <label>ID Lowongan</label>
                        <input type="text" class="form-control"
                            value="{{ $generatedId ?? 'Auto Generate' }}"
                            readonly>
                    </div>
                </div>
                @endif

                {{-- Judul --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Judul Lowongan</label>
                        <input type="text" name="judul_lowongan"
                            class="form-control @error('judul_lowongan') is-invalid @enderror"
                            placeholder="Contoh: Staff IT Support"
                            value="{{ old('judul_lowongan', $lowongan->judul_lowongan ?? '') }}">
                        @error('judul_lowongan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi"
                            class="form-control @error('lokasi') is-invalid @enderror"
                            placeholder="Contoh: Jayapura"
                            value="{{ old('lokasi', $lowongan->lokasi ?? '') }}">
                        @error('lokasi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Jenis --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Pekerjaan</label>
                        <select name="jenis_pekerjaan" class="form-control">
                            <option value="">-- Pilih Jenis Pekerjaan --</option>
                            @foreach(['fulltime','parttime','freelance'] as $item)
                            <option value="{{ $item }}"
                                {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan ?? '') == $item ? 'selected' : '' }}>
                                {{ ucfirst($item) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sistem --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sistem Kerja</label>
                        <select name="sistem_kerja" class="form-control">
                            <option value="">-- Pilih Sistem Kerja --</option>
                            @foreach(['onsite','remote','hybrid'] as $item)
                            <option value="{{ $item }}"
                                {{ old('sistem_kerja', $lowongan->sistem_kerja ?? '') == $item ? 'selected' : '' }}>
                                {{ ucfirst($item) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Gaji --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gaji Minimum</label>
                        <input type="text" id="gaji_minimum" name="gaji_minimum"
                            class="form-control"
                            placeholder="Contoh: 3.000.000"
                            value="{{ old('gaji_minimum', $lowongan->gaji_minimum ?? '') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gaji Maksimum</label>
                        <input type="text" id="gaji_maksimum" name="gaji_maksimum"
                            class="form-control"
                            placeholder="Contoh: 7.000.000"
                            value="{{ old('gaji_maksimum', $lowongan->gaji_maksimum ?? '') }}">
                    </div>
                </div>

                {{-- Pendidikan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pendidikan Minimum</label>
                        <input type="text" name="pendidikan_minimum" class="form-control"
                            placeholder="Contoh: SMA / D3 / S1"
                            value="{{ old('pendidikan_minimum', $lowongan->pendidikan_minimum ?? '') }}">
                    </div>
                </div>

                {{-- Pengalaman --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengalaman Minimum</label>
                        <input type="text" name="pengalaman_minimum" class="form-control"
                            placeholder="Contoh: 1 tahun"
                            value="{{ old('pengalaman_minimum', $lowongan->pengalaman_minimum ?? '') }}">
                    </div>
                </div>

                {{-- Kuota --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kuota</label>
                        <input type="number" name="kuota" class="form-control"
                            placeholder="Minimal 1"
                            value="{{ old('kuota', $lowongan->kuota ?? '') }}">
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                            value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai ?? '') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir" class="form-control"
                            value="{{ old('tanggal_berakhir', $lowongan->tanggal_berakhir ?? '') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>

                        @php
                        $status = $lowongan->status ?? 'pending';
                        @endphp

                        <input type="text" class="form-control 
            {{ $status == 'pending' ? 'bg-warning text-dark' : '' }}
            {{ $status == 'disetujui' ? 'bg-success text-white' : '' }}
            {{ $status == 'ditolak' ? 'bg-danger text-white' : '' }}"
                            value="{{ ucfirst($status) }}"
                            readonly>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"
                            placeholder="Jelaskan pekerjaan, kualifikasi, dan tanggung jawab">{{ old('deskripsi', $lowongan->deskripsi ?? '') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">

            {{-- Kembali --}}
            <a href="{{ route('perusahaan.lowongan.index') }}"
                class="btn btn-outline-secondary mr-2 btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- Simpan / Update --}}
            <button type="button"
                class="btn btn-primary btn-submit">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>

        </div>
    </form>
</div>
