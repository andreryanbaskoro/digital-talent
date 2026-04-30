@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Pengalaman Kerja' }}</h1>
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
                                {{ $editData ? 'Edit Pengalaman Kerja' : 'Tambah Pengalaman Kerja' }}
                            </strong>
                        </div>

                        <div class="card-body">
                            <form action="{{ $editData 
                                ? route('pencaker.ak1.pengalaman.update', $editData->id_pengalaman_kerja) 
                                : route('pencaker.ak1.pengalaman.store') }}"
                                method="POST">

                                @csrf
                                @if($editData)
                                @method('PUT')
                                @endif

                                {{-- Nama Perusahaan --}}
                                <div class="mb-3">
                                    <label>Nama Perusahaan</label>
                                    <input type="text"
                                        name="nama_perusahaan"
                                        value="{{ old('nama_perusahaan', $editData->nama_perusahaan ?? '') }}"
                                        class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                        required>
                                    @error('nama_perusahaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div class="mb-3">
                                    <label>Jabatan</label>
                                    <input type="text"
                                        name="jabatan"
                                        value="{{ old('jabatan', $editData->jabatan ?? '') }}"
                                        class="form-control @error('jabatan') is-invalid @enderror"
                                        required>
                                    @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tanggal --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Mulai Bekerja</label>
                                        <input type="date"
                                            name="mulai_bekerja"
                                            value="{{ old('mulai_bekerja', isset($editData) ? optional($editData->mulai_bekerja)->format('Y-m-d') : '') }}"
                                            class="form-control @error('mulai_bekerja') is-invalid @enderror"
                                            required>
                                        @error('mulai_bekerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Selesai Bekerja</label>
                                        <input type="date"
                                            name="selesai_bekerja"
                                            value="{{ old('selesai_bekerja', isset($editData) ? optional($editData->selesai_bekerja)->format('Y-m-d') : '') }}"
                                            class="form-control @error('selesai_bekerja') is-invalid @enderror">
                                        @error('selesai_bekerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="mb-3">
                                    <label>Deskripsi (Opsional)</label>
                                    <textarea name="deskripsi"
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        rows="3">{{ old('deskripsi', $editData->deskripsi ?? '') }}</textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="button" class="btn btn-warning btn-submit w-100">
                                    <i class="fas fa-save"></i>
                                    {{ $editData ? 'Update Pengalaman' : 'Simpan Pengalaman' }}
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- ================= LIST ================= --}}
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <strong>Daftar Pengalaman Kerja</strong>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Perusahaan</th>
                                        <th>Periode</th>
                                        <th width="120" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($pengalaman ?? [] as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->nama_perusahaan }}</strong> - {{ $item->jabatan }}<br>
                                            <small>{{ $item->deskripsi }}</small><br>
                                            <small class="text-muted">
                                                ID: {{ $item->id_pengalaman_kerja }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ optional($item->mulai_bekerja)->format('d M Y') }}
                                            -
                                            {{ optional($item->selesai_bekerja)->format('d M Y') ?? 'Sekarang' }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">

                                                {{-- Edit --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-primary btn-edit mr-2"
                                                    data-id="{{ $item->id_pengalaman_kerja }}"
                                                    data-nama="{{ $item->nama_perusahaan }}"
                                                    data-jabatan="{{ $item->jabatan }}"
                                                    data-mulai="{{ optional($item->mulai_bekerja)->format('Y-m-d') }}"
                                                    data-selesai="{{ optional($item->selesai_bekerja)->format('Y-m-d') }}"
                                                    data-deskripsi="{{ $item->deskripsi }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Hapus --}}

                                                <form action="{{ route('pencaker.ak1.pengalaman.destroy', $item->id_pengalaman_kerja) }}"
                                                    method="POST"
                                                    class="form-hapus">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        class="btn btn-hapus btn-sm btn-danger"
                                                        data-url="{{ route('pencaker.ak1.pengalaman.destroy', $item->id_pengalaman_kerja) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada pengalaman kerja.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        @if(!empty($pengalaman) && $pengalaman->count())
                        <div class="card-footer text-muted small">
                            Total: {{ $pengalaman->count() }} pengalaman kerja
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
<script src="{{ asset('admin-js/pencaker-pengalaman.js') }}"></script>
@endpush