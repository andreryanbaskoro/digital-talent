@php
$isEdit = isset($ak1);

$keterampilanItems = old('keterampilan');
if ($keterampilanItems === null) {
$keterampilanItems = isset($ak1) ? $ak1->keterampilan->map(function ($item) {
return [
'nama_keterampilan' => $item->nama_keterampilan,
'tingkat' => $item->tingkat,
'sertifikat_path' => $item->sertifikat,
];
})->toArray() : [];
}

$pengalamanItems = old('pengalaman');
if ($pengalamanItems === null) {
$pengalamanItems = isset($ak1) ? $ak1->pengalamanKerja->map(function ($item) {
return [
'nama_perusahaan' => $item->nama_perusahaan,
'jabatan' => $item->jabatan,
'mulai_bekerja' => $item->mulai_bekerja,
'selesai_bekerja' => $item->selesai_bekerja,
'deskripsi' => $item->deskripsi,
];
})->toArray() : [];
}

$pendidikanItems = old('pendidikan');
if ($pendidikanItems === null) {
$pendidikanItems = isset($ak1) ? $ak1->riwayatPendidikan->map(function ($item) {
return [
'jenjang' => $item->jenjang,
'nama_sekolah' => $item->nama_sekolah,
'jurusan' => $item->jurusan,
'tahun_masuk' => $item->tahun_masuk,
'tahun_lulus' => $item->tahun_lulus,
'nilai_akhir' => $item->nilai_akhir,
];
})->toArray() : [];
}
@endphp

@if($errors->any())
<div class="alert alert-danger">
    <strong>Terjadi kesalahan.</strong>
    Silakan cek kembali isian form.
</div>
@endif

<div class="row">

    {{-- ================= DATA PENCARI KERJA ================= --}}
    <div class="col-12 mb-3">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Data Pencari Kerja</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">Nama Lengkap</label>
                        <div class="form-control bg-light">
                            {{ $profil->nama_lengkap ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">NIK</label>
                        <div class="form-control bg-light">
                            {{ $profil->nik ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">No KK</label>
                        <div class="form-control bg-light">
                            {{ $profil->nomor_kk ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">Tempat, Tanggal Lahir</label>
                        <div class="form-control bg-light">
                            {{ $profil->tempat_lahir ?? '-' }},
                            {{ !empty($profil->tanggal_lahir) ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d-m-Y') : '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">Jenis Kelamin</label>
                        <div class="form-control bg-light">
                            @if(($profil->jenis_kelamin ?? '') == 'L')
                            Laki-laki
                            @elseif(($profil->jenis_kelamin ?? '') == 'P')
                            Perempuan
                            @else
                            -
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">No HP</label>
                        <div class="form-control bg-light">
                            {{ $profil->nomor_hp ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted">Email</label>
                        <div class="form-control bg-light">
                            {{ $profil->email ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="text-muted">Alamat</label>
                        <div class="form-control bg-light" style="min-height: 70px; height: auto;">
                            {{ $profil->alamat ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= DOKUMEN ================= --}}
    <form action="{{ $isEdit ? route('ak1.dokumen.update', $ak1->id_kartu_ak1) : route('ak1.store') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Dokumen Persyaratan</h3>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Foto Pas</label>
                        <input type="file" name="foto_pas" class="form-control @error('foto_pas') is-invalid @enderror">

                        @error('foto_pas')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                        @if($isEdit && !empty($ak1->foto_pas))
                        <small class="d-block mt-2">
                            File saat ini:
                            <a href="{{ asset('storage/'.$ak1->foto_pas) }}" target="_blank">Lihat</a>
                        </small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Scan KTP</label>
                        <input type="file" name="scan_ktp" class="form-control @error('scan_ktp') is-invalid @enderror">

                        @error('scan_ktp')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                        @if($isEdit && !empty($ak1->scan_ktp))
                        <small class="d-block mt-2">
                            File saat ini:
                            <a href="{{ asset('storage/'.$ak1->scan_ktp) }}" target="_blank">Lihat</a>
                        </small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Scan Ijazah</label>
                        <input type="file" name="scan_ijazah" class="form-control @error('scan_ijazah') is-invalid @enderror">

                        @error('scan_ijazah')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                        @if($isEdit && !empty($ak1->scan_ijazah))
                        <small class="d-block mt-2">
                            File saat ini:
                            <a href="{{ asset('storage/'.$ak1->scan_ijazah) }}" target="_blank">Lihat</a>
                        </small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Scan KK</label>
                        <input type="file" name="scan_kk" class="form-control @error('scan_kk') is-invalid @enderror">

                        @error('scan_kk')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                        @if($isEdit && !empty($ak1->scan_kk))
                        <small class="d-block mt-2">
                            File saat ini:
                            <a href="{{ asset('storage/'.$ak1->scan_kk) }}" target="_blank">Lihat</a>
                        </small>
                        @endif
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Simpan Dokumen
                </button>
            </div>
        </div>
    </form>

    {{-- ================= KETERAMPILAN ================= --}}
    <form action="{{ $isEdit ? route('ak1.keterampilan.update', $ak1->id_kartu_ak1) : route('ak1.store') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card card-outline card-success shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">Keterampilan</h3>
                <button type="button" class="btn btn-sm btn-success" onclick="addKeterampilan()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <div class="card-body">
                <div id="keterampilan-wrapper"></div>

                @if(empty($keterampilanItems))
                <div class="text-muted">Belum ada data keterampilan.</div>
                @else
                @foreach($keterampilanItems as $index => $item)
                <div class="card mb-2 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Nama Keterampilan</label>
                                <input type="text"
                                    name="keterampilan[{{ $index }}][nama_keterampilan]"
                                    class="form-control"
                                    value="{{ old("keterampilan.$index.nama_keterampilan", $item['nama_keterampilan'] ?? '') }}"
                                    placeholder="Contoh: Laravel">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Tingkat</label>
                                <input type="text"
                                    name="keterampilan[{{ $index }}][tingkat]"
                                    class="form-control"
                                    value="{{ old("keterampilan.$index.tingkat", $item['tingkat'] ?? '') }}"
                                    placeholder="Contoh: Dasar / Mahir">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>Sertifikat</label>
                                <input type="file"
                                    name="keterampilan[{{ $index }}][sertifikat]"
                                    class="form-control">

                                @if(!empty($item['sertifikat_path'] ?? null))
                                <small class="d-block mt-2">
                                    File saat ini:
                                    <a href="{{ asset('storage/'.$item['sertifikat_path']) }}" target="_blank">Lihat</a>
                                </small>
                                @endif
                            </div>

                            <div class="col-md-1 d-flex align-items-end mb-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.card').remove()">
                                    X
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <button type="submit" class="btn btn-success mt-3">
                    Simpan Keterampilan
                </button>
            </div>
        </div>
    </form>

    {{-- ================= PENGALAMAN KERJA ================= --}}
    <form action="{{ $isEdit ? route('ak1.pengalaman.update', $ak1->id_kartu_ak1) : route('ak1.store') }}"
        method="POST">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card card-outline card-info shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">Pengalaman Kerja</h3>
                <button type="button" class="btn btn-sm btn-info" onclick="addPengalaman()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <div class="card-body">
                <div id="pengalaman-wrapper"></div>

                @if(empty($pengalamanItems))
                <div class="text-muted">Belum ada data pengalaman kerja.</div>
                @else
                @foreach($pengalamanItems as $index => $item)
                <div class="card mb-2 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Nama Perusahaan</label>
                                <input type="text"
                                    name="pengalaman[{{ $index }}][nama_perusahaan]"
                                    class="form-control"
                                    value="{{ old("pengalaman.$index.nama_perusahaan", $item['nama_perusahaan'] ?? '') }}"
                                    placeholder="Contoh: PT Maju Jaya">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>Jabatan</label>
                                <input type="text"
                                    name="pengalaman[{{ $index }}][jabatan]"
                                    class="form-control"
                                    value="{{ old("pengalaman.$index.jabatan", $item['jabatan'] ?? '') }}"
                                    placeholder="Contoh: Admin">
                            </div>

                            <div class="col-md-2 mb-2">
                                <label>Mulai Bekerja</label>
                                <input type="date"
                                    name="pengalaman[{{ $index }}][mulai_bekerja]"
                                    class="form-control"
                                    value="{{ old("pengalaman.$index.mulai_bekerja", $item['mulai_bekerja'] ?? '') }}">
                            </div>

                            <div class="col-md-2 mb-2">
                                <label>Selesai Bekerja</label>
                                <input type="date"
                                    name="pengalaman[{{ $index }}][selesai_bekerja]"
                                    class="form-control"
                                    value="{{ old("pengalaman.$index.selesai_bekerja", $item['selesai_bekerja'] ?? '') }}">
                            </div>

                            <div class="col-md-11 mb-2">
                                <label>Deskripsi</label>
                                <textarea name="pengalaman[{{ $index }}][deskripsi]"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Deskripsi pekerjaan">{{ old("pengalaman.$index.deskripsi", $item['deskripsi'] ?? '') }}</textarea>
                            </div>

                            <div class="col-md-1 d-flex align-items-end mb-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.card').remove()">
                                    X
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <button type="submit" class="btn btn-info mt-3">
                    Simpan Pengalaman
                </button>
            </div>
        </div>
    </form>

    {{-- ================= PENDIDIKAN ================= --}}
      <form action="{{ $isEdit ? route('ak1.pendidikan.update', $ak1->id_kartu_ak1) : route('ak1.store') }}"
        method="POST">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card card-outline card-warning shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">Riwayat Pendidikan</h3>
                <button type="button" class="btn btn-sm btn-warning" onclick="addPendidikan()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <div class="card-body">
                <div id="pendidikan-wrapper"></div>

                @if(empty($pendidikanItems))
                <div class="text-muted">Belum ada data riwayat pendidikan.</div>
                @else
                @foreach($pendidikanItems as $index => $item)
                <div class="card mb-2 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Jenjang</label>
                                <input type="text"
                                    name="pendidikan[{{ $index }}][jenjang]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.jenjang", $item['jenjang'] ?? '') }}"
                                    placeholder="Contoh: SMA / S1">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>Nama Sekolah</label>
                                <input type="text"
                                    name="pendidikan[{{ $index }}][nama_sekolah]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.nama_sekolah", $item['nama_sekolah'] ?? '') }}"
                                    placeholder="Contoh: SMA Negeri 1">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Jurusan</label>
                                <input type="text"
                                    name="pendidikan[{{ $index }}][jurusan]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.jurusan", $item['jurusan'] ?? '') }}"
                                    placeholder="Contoh: IPA">
                            </div>

                            <div class="col-md-2 mb-2">
                                <label>Tahun Masuk</label>
                                <input type="number"
                                    name="pendidikan[{{ $index }}][tahun_masuk]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.tahun_masuk", $item['tahun_masuk'] ?? '') }}"
                                    placeholder="2020">
                            </div>

                            <div class="col-md-2 mb-2">
                                <label>Tahun Lulus</label>
                                <input type="number"
                                    name="pendidikan[{{ $index }}][tahun_lulus]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.tahun_lulus", $item['tahun_lulus'] ?? '') }}"
                                    placeholder="2023">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Nilai Akhir</label>
                                <input type="number"
                                    step="0.01"
                                    name="pendidikan[{{ $index }}][nilai_akhir]"
                                    class="form-control"
                                    value="{{ old("pendidikan.$index.nilai_akhir", $item['nilai_akhir'] ?? '') }}"
                                    placeholder="0.00">
                            </div>

                            <div class="col-md-1 d-flex align-items-end mb-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.card').remove()">
                                    X
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <button type="submit" class="btn btn-warning mt-3">
                    Simpan Pendidikan
                </button>
            </div>
        </div>
    </form>
</div>