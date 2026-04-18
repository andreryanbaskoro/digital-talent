@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Riwayat Pendidikan' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.pencaker.kartu-ak1.formulir.alerts')

            <div class="row">

                {{-- ================= FORM TAMBAH / EDIT ================= --}}
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <strong>
                                {{ $editData ? 'Edit Riwayat Pendidikan' : 'Tambah Riwayat Pendidikan' }}
                            </strong>
                        </div>

                        <div class="card-body">
                            <form action="{{ $editData 
                                ? route('pencaker.ak1.riwayat.update', $editData->id_riwayat_pendidikan) 
                                : route('pencaker.ak1.riwayat.store') }}"
                                method="POST">

                                @csrf
                                @if($editData)
                                @method('PUT')
                                @endif

                                {{-- Jenjang --}}
                                <div class="mb-3">
                                    <label>Jenjang</label>
                                    <select name="jenjang"
                                        class="form-control @error('jenjang') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Jenjang --</option>
                                        @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $jenjang)
                                        <option value="{{ $jenjang }}"
                                            {{ old('jenjang', $editData->jenjang ?? '') == $jenjang ? 'selected' : '' }}>
                                            {{ $jenjang }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jenjang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nama Sekolah --}}
                                <div class="mb-3">
                                    <label>Nama Sekolah / Universitas</label>
                                    <input type="text"
                                        name="nama_sekolah"
                                        placeholder="Contoh: Universitas Sains & Teknologi Jayapura"
                                        value="{{ old('nama_sekolah', $editData->nama_sekolah ?? '') }}"
                                        class="form-control @error('nama_sekolah') is-invalid @enderror"
                                        required>
                                    @error('nama_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Jurusan --}}
                                <div class="mb-3">
                                    <label>Jurusan</label>
                                    <input type="text"
                                        name="jurusan"
                                        placeholder="Contoh: Teknik Informatika"
                                        value="{{ old('jurusan', $editData->jurusan ?? '') }}"
                                        class="form-control @error('jurusan') is-invalid @enderror">
                                    @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tahun --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tahun Masuk</label>
                                        <input type="number"
                                            name="tahun_masuk"
                                            placeholder="Contoh: 2019"
                                            min="1900"
                                            max="{{ date('Y') }}"
                                            value="{{ old('tahun_masuk', $editData->tahun_masuk ?? '') }}"
                                            class="form-control @error('tahun_masuk') is-invalid @enderror">
                                        @error('tahun_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Tahun Lulus</label>
                                        <input type="number"
                                            name="tahun_lulus"
                                            placeholder="Contoh: 2023"
                                            min="1900"
                                            max="{{ date('Y') }}"
                                            value="{{ old('tahun_lulus', $editData->tahun_lulus ?? '') }}"
                                            class="form-control @error('tahun_lulus') is-invalid @enderror">
                                        @error('tahun_lulus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nilai Akhir --}}
                                <div class="mb-3">
                                    <label>Nilai Akhir / IPK</label>
                                    <input type="number"
                                        step="0.01"
                                        name="nilai_akhir"
                                        placeholder="Contoh: 4.00"
                                        value="{{ old('nilai_akhir', $editData->nilai_akhir ?? '') }}"
                                        class="form-control @error('nilai_akhir') is-invalid @enderror">
                                    @error('nilai_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-save"></i>
                                    {{ $editData ? 'Update Pendidikan' : 'Simpan Pendidikan' }}
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- ================= LIST ================= --}}
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <strong>Daftar Riwayat Pendidikan</strong>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pendidikan</th>
                                        <th>Periode</th>
                                        <th width="120" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($pendidikan ?? [] as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->jenjang }} - {{ $item->jurusan }} | {{ $item->nilai_akhir ?? '-' }} </strong><br>
                                            {{ $item->nama_sekolah }}<br>
                                            <small class="text-muted">
                                                ID: {{ $item->id_riwayat_pendidikan }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $item->tahun_masuk ?? '-' }}
                                            -
                                            {{ $item->tahun_lulus ?? 'Sekarang' }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">

                                                {{-- Edit --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-primary btn-edit mr-2"
                                                    data-id="{{ $item->id_riwayat_pendidikan }}"
                                                    data-jenjang="{{ $item->jenjang }}"
                                                    data-nama="{{ $item->nama_sekolah }}"
                                                    data-jurusan="{{ $item->jurusan }}"
                                                    data-masuk="{{ $item->tahun_masuk }}"
                                                    data-lulus="{{ $item->tahun_lulus }}"
                                                    data-nilai="{{ $item->nilai_akhir }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Hapus --}}
                                                <form action="{{ route('pencaker.ak1.riwayat.destroy', $item->id_riwayat_pendidikan) }}"
                                                    method="POST"
                                                    class="form-hapus">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        class="btn btn-hapus btn-sm btn-danger"
                                                        data-url="{{ route('pencaker.ak1.riwayat.destroy', $item->id_riwayat_pendidikan) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada riwayat pendidikan.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        @if(!empty($pendidikan) && $pendidikan->count())
                        <div class="card-footer text-muted small">
                            Total: {{ $pendidikan->count() }} riwayat pendidikan
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection


@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/pencaker-pendidikan.js') }}"></script>
@endpush