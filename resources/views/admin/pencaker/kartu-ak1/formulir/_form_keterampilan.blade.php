@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Keterampilan' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.pencaker.kartu-ak1.formulir.alerts')

            <div class="row">

                {{-- ================= FORM ================= --}}
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <strong>Tambah Keterampilan</strong>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('pencaker.ak1.keterampilan.store') }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label class="form-label">Nama Keterampilan</label>
                                    <input type="text"
                                        name="nama_keterampilan"
                                        value="{{ old('nama_keterampilan') }}"
                                        class="form-control @error('nama_keterampilan') is-invalid @enderror"
                                        placeholder="Contoh: Microsoft Excel, Desain Grafis"
                                        required>
                                    @error('nama_keterampilan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tingkat --}}
                                <div class="mb-3">
                                    <label class="form-label">Tingkat Keahlian</label>
                                    <select name="tingkat"
                                        class="form-control @error('tingkat') is-invalid @enderror">

                                        <option value="">-- Pilih Tingkat Keahlian --</option>

                                        <option value="Sangat ahli"
                                            {{ old('tingkat', $editData->tingkat ?? '') == 'Sangat ahli' ? 'selected' : '' }}>
                                            Sangat ahli (tersertifikasi & mahir penuh)
                                        </option>

                                        <option value="Mahir"
                                            {{ old('tingkat', $editData->tingkat ?? '') == 'Mahir' ? 'selected' : '' }}>
                                            Mahir (bisa bekerja mandiri)
                                        </option>

                                        <option value="Cukup mahir"
                                            {{ old('tingkat', $editData->tingkat ?? '') == 'Cukup mahir' ? 'selected' : '' }}>
                                            Cukup mahir (sedikit bimbingan)
                                        </option>

                                        <option value="Dasar"
                                            {{ old('tingkat', $editData->tingkat ?? '') == 'Dasar' ? 'selected' : '' }}>
                                            Dasar (mengetahui konsep)
                                        </option>

                                        <option value="Tidak relevan"
                                            {{ old('tingkat', $editData->tingkat ?? '') == 'Tidak relevan' ? 'selected' : '' }}>
                                            Tidak memiliki skill relevan
                                        </option>

                                    </select>

                                    @error('tingkat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Sertifikat --}}
                                <div class="mb-3">
                                    <label class="form-label">Upload Sertifikat (Opsional)</label>
                                    <input type="file"
                                        name="sertifikat"
                                        class="form-control @error('sertifikat') is-invalid @enderror">
                                    <small class="text-muted">
                                        Format: JPG, PNG, PDF (Max 2MB)
                                    </small>
                                    @error('sertifikat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="button" class="btn btn-warning btn-submit w-100">
                                    <i class="fas fa-save"></i> Simpan Keterampilan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ================= LIST ================= --}}
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <strong>Daftar Keterampilan</strong>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th width="130">Tingkat</th>
                                        <th width="120">Sertifikat</th>
                                        <th width="90" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                    $badge = [
                                    'Pemula' => 'secondary',
                                    'Menengah' => 'primary',
                                    'Mahir' => 'success'
                                    ];
                                    @endphp

                                    @forelse($keterampilan ?? [] as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->nama_keterampilan }}</strong><br>
                                            <small class="text-muted">
                                                ID: {{ $item->id_keterampilan }}
                                            </small>
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $badge[$item->tingkat] ?? 'secondary' }}">
                                                {{ $item->tingkat }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($item->sertifikat)
                                            <a href="{{ Storage::url($item->sertifikat) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-info">
                                                Lihat
                                            </a>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <form action="{{ route('pencaker.ak1.keterampilan.destroy', $item->id_keterampilan) }}"
                                                method="POST"
                                                class="form-hapus">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="btn btn-hapus btn-sm btn-danger"
                                                    data-url="{{ route('pencaker.ak1.keterampilan.destroy', $item->id_keterampilan) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada keterampilan yang ditambahkan.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        @if(!empty($keterampilan) && $keterampilan->count())
                        <div class="card-footer text-muted small">
                            Total: {{ $keterampilan->count() }} keterampilan
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
@endpush