@php
$isEdit = isset($lowongan);
$kriteria = $isEdit ? $lowongan->kriteria->keyBy('nama_kriteria') : collect();
$subKriteria = $isEdit ? $lowongan->subKriteriaLowongan->values() : collect();
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
                        <select name="jenis_pekerjaan" class="form-control @error('jenis_pekerjaan') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Pekerjaan --</option>

                            @foreach([
                            'fulltime' => 'Full Time',
                            'parttime' => 'Part Time',
                            'freelance' => 'Freelance',
                            'internship' => 'Internship'
                            ] as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('jenis_pekerjaan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Sistem Kerja --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipe Lokasi Kerja</label>

                        <select name="sistem_kerja" class="form-control @error('sistem_kerja') is-invalid @enderror">
                            <option value="">-- Pilih Tipe Lokasi Kerja --</option>

                            @foreach([
                            'onsite' => 'Kerja di Kantor',
                            'remote' => 'Kerja dari Rumah',
                            'hybrid' => 'WFO & WFH (Hybrid)'
                            ] as $value => $label)

                            <option value="{{ $value }}"
                                {{ old('sistem_kerja', $lowongan->sistem_kerja ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>

                            @endforeach
                        </select>
                        @error('sistem_kerja')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Gaji --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gaji Minimum</label>
                        <input type="text" id="gaji_minimum" name="gaji_minimum"
                            class="form-control @error('gaji_minimum') is-invalid @enderror"
                            placeholder="Contoh: 3.000.000"
                            value="{{ old('gaji_minimum', $lowongan->gaji_minimum ?? '') }}">
                        @error('gaji_minimum')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gaji Maksimum</label>
                        <input type="text" id="gaji_maksimum" name="gaji_maksimum"
                            class="form-control @error('gaji_maksimum') is-invalid @enderror"
                            placeholder="Contoh: 7.000.000"
                            value="{{ old('gaji_maksimum', $lowongan->gaji_maksimum ?? '') }}">
                        @error('gaji_maksimum')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Pendidikan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pendidikan Minimum</label>
                        <input type="text" name="pendidikan_minimum" class="form-control @error('pendidikan_minimum') is-invalid @enderror"
                            placeholder="Contoh: SMA / D3 / S1"
                            value="{{ old('pendidikan_minimum', $lowongan->pendidikan_minimum ?? '') }}">
                        @error('pendidikan_minimum')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Pengalaman --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengalaman Minimum</label>
                        <input type="text" name="pengalaman_minimum" class="form-control @error('pengalaman_minimum') is-invalid @enderror"
                            placeholder="Contoh: 1 tahun"
                            value="{{ old('pengalaman_minimum', $lowongan->pengalaman_minimum ?? '') }}">
                        @error('pengalaman_minimum')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kuota --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kuota</label>
                        <input type="number" name="kuota" class="form-control @error('kuota') is-invalid @enderror"
                            placeholder="Minimal 1"
                            value="{{ old('kuota', $lowongan->kuota ?? '') }}">
                        @error('kuota')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai ?? '') }}">
                        @error('tanggal_mulai')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir" class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                            value="{{ old('tanggal_berakhir', $lowongan->tanggal_berakhir ?? '') }}">
                        @error('tanggal_berakhir')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4"
                            placeholder="Jelaskan pekerjaan, kualifikasi, dan tanggung jawab">{{ old('deskripsi', $lowongan->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="card card-outline card-success mx-3 mb-3">
            <div class="card-header">
                <h3 class="card-title">Kriteria & Sub Kriteria (Profile Matching)</h3>
            </div>

            <div class="card-body">

                {{-- ================= KRITERIA UTAMA ================= --}}
                <div class="row">

                    {{-- Pengalaman --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pengalaman <small class="text-danger">(Core)</small></label>
                            <select name="kriteria[pengalaman][nilai_target]" class="form-control">
                                <option value="">-- Pilih --</option>
                                @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}"
                                    {{ ($kriteria['pengalaman']->nilai_target ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i == 5 ? '> 5 tahun' :
               ($i == 4 ? '3–5 tahun' :
               ($i == 3 ? '1–2 tahun' :
               ($i == 2 ? '< 1 tahun' : 'Tidak ada'))) }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Pendidikan --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pendidikan <small class="text-primary">(Secondary)</small></label>
                            <select name="kriteria[pendidikan][nilai_target]" class="form-control">
                                <option value="">-- Pilih --</option>
                                @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}"
                                    {{ ($kriteria['pendidikan']->nilai_target ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i == 5 ? 'S2 / S1 relevan' :
               ($i == 4 ? 'S1 sesuai' :
               ($i == 3 ? 'D3 sesuai' :
               ($i == 2 ? 'SMA/SMK' : 'Tidak relevan'))) }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Lokasi <small class="text-primary">(Secondary)</small></label>
                            <select name="kriteria[lokasi][nilai_target]" class="form-control">
                                <option value="">-- Pilih --</option>
                                @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}"
                                    {{ ($kriteria['lokasi']->nilai_target ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i == 5 ? 'Satu lokasi' :
               ($i == 4 ? 'Wilayah dekat' :
               ($i == 3 ? '1 provinsi' :
               ($i == 2 ? 'Luar provinsi' : 'Tidak bersedia'))) }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </div>

                <hr>

                {{-- ================= SUB KRITERIA ================= --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Skill yang Dibutuhkan</h5>
                    <button type="button" id="btn-add-skill" class="btn btn-sm btn-success">
                        + Tambah Skill
                    </button>
                </div>

                <div id="skill-wrapper"
                    data-count="{{ old('sub_kriteria') ? count(old('sub_kriteria')) : $subKriteria->count() }}">
                    @forelse($subKriteria as $i => $item)
                    <div class="row mb-2 skill-item align-items-center">
                        <div class="col-md-6">
                            <input type="text"
                                name="sub_kriteria[{{ $i }}][nama]"
                                class="form-control"
                                value="{{ old("sub_kriteria.$i.nama", $item->subKriteria->nama_sub_kriteria ?? '') }}"
                                placeholder="Skill">
                        </div>

                        <div class="col-md-4">
                            <select name="sub_kriteria[{{ $i }}][nilai_target]" class="form-control">
                                <option value="">-- Pilih Level --</option>
                                @for ($j = 5; $j >= 1; $j--)
                                <option value="{{ $j }}"
                                    {{ old("sub_kriteria.$i.nilai_target", $item->nilai_target) == $j ? 'selected' : '' }}>
                                    {{ $j }} - {{ ['Tidak bisa','Dasar','Cukup','Mahir','Sangat ahli'][$j-1] }}
                                </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2 text-right">
                            <button type="button" class="btn btn-danger btn-remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="row mb-2 skill-item">
                        <div class="col-md-6">
                            <input type="text"
                                name="sub_kriteria[0][nama]"
                                class="form-control"
                                value="{{ old('sub_kriteria.0.nama') }}"
                                placeholder="Skill">
                        </div>
                        <div class="col-md-4">
                            <select name="sub_kriteria[0][nilai_target]" class="form-control">
                                <option value="">-- Pilih Level --</option>
                                @for ($j = 5; $j >= 1; $j--)
                                <option value="{{ $j }}"
                                    {{ old('sub_kriteria.0.nilai_target') == $j ? 'selected' : '' }}>
                                    {{ $j }} - {{ ['Tidak bisa','Dasar','Cukup','Mahir','Sangat ahli'][$j-1] }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    @endforelse
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