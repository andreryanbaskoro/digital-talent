@extends('layouts.app-admin')

@section('content')

@php
$isLocked = isset($kartuAk1) && in_array($kartuAk1->status, ['pending', 'disetujui']);
$status = $kartuAk1->status ?? 'draft';
@endphp

<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <h1 class="mb-0">{{ $title ?? 'Dokumen Pribadi' }}</h1>

            @if(isset($kartuAk1))
            @switch($status)
            @case('pending')
            <span class="badge badge-warning p-2">⏳ Menunggu Verifikasi</span>
            @break

            @case('disetujui')
            <span class="badge badge-success p-2">✅ Disetujui</span>
            @break

            @default
            <span class="badge badge-secondary p-2">📝 Draft</span>
            @endswitch
            @endif

        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.pencaker.profil-pencaker.partials.alerts')

            {{-- ================= INFO STATUS ================= --}}
            @if($isLocked)
            <div class="card border-warning mb-3">
                <div class="card-body d-flex align-items-center">

                    <div class="mr-3 text-warning" style="font-size: 22px;">
                        ⚠️
                    </div>

                    <div>
                        <h6 class="mb-1 text-warning font-weight-bold">
                            Dokumen tidak dapat diubah
                        </h6>
                        <small class="text-muted">
                            Status AK1 saat ini sudah
                            <b>{{ $status }}</b>.
                            Semua form pengunggahan telah dikunci.
                        </small>
                    </div>

                </div>
            </div>
            @else
            <div class="card border-info mb-3">
                <div class="card-body d-flex align-items-center">

                    <div class="mr-3 text-info" style="font-size: 22px;">
                        ℹ️
                    </div>

                    <div>
                        <h6 class="mb-1 text-info font-weight-bold">
                            Lengkapi Dokumen Anda
                        </h6>
                        <small class="text-muted">
                            Silakan unggah seluruh dokumen yang diperlukan untuk proses AK1.
                            Pastikan file dalam format JPG, PNG, atau PDF.
                        </small>
                    </div>

                </div>
            </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-12">

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Dokumen</th>
                                <th>Unggah & Aksi</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- ================= FOTO PAS ================= --}}
                            <tr>
                                <td>Foto Pas</td>
                                <td>
                                    <form action="{{ route('pencaker.ak1.dokumen.upload', ['type' => 'foto_pas']) }}"
                                        method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex align-items-center">

                                            <input type="file"
                                                name="foto_pas"
                                                class="form-control"
                                                @if($isLocked) disabled @endif>

                                            <button type="submit"
                                                class="btn ml-3 btn-primary"
                                                @if($isLocked) disabled @endif>
                                                {{ isset($foto_pas) ? 'Ganti' : 'Unggah' }}
                                            </button>

                                            @if(isset($foto_pas))
                                            <a href="{{ Storage::url($foto_pas) }}"
                                                target="_blank"
                                                class="btn btn-outline-primary ml-2">
                                                Lihat
                                            </a>
                                            @endif

                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    @if(isset($foto_pas))
                                    <span class="text-success">✔ Sudah diunggah</span>
                                    @else
                                    <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- ================= KTP ================= --}}
                            <tr>
                                <td>Scan KTP</td>
                                <td>
                                    <form action="{{ route('pencaker.ak1.dokumen.upload', ['type' => 'scan_ktp']) }}"
                                        method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex align-items-center">

                                            <input type="file"
                                                name="scan_ktp"
                                                class="form-control"
                                                @if($isLocked) disabled @endif>

                                            <button type="submit"
                                                class="btn ml-3 btn-success"
                                                @if($isLocked) disabled @endif>
                                                {{ isset($scan_ktp) ? 'Ganti' : 'Unggah' }}
                                            </button>

                                            @if(isset($scan_ktp))
                                            <a href="{{ Storage::url($scan_ktp) }}"
                                                target="_blank"
                                                class="btn btn-outline-success ml-2">
                                                Lihat
                                            </a>
                                            @endif

                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    @if(isset($scan_ktp))
                                    <span class="text-success">✔ Sudah diunggah</span>
                                    @else
                                    <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- ================= IJAZAH ================= --}}
                            <tr>
                                <td>Scan Ijazah</td>
                                <td>
                                    <form action="{{ route('pencaker.ak1.dokumen.upload', ['type' => 'scan_ijazah']) }}"
                                        method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex align-items-center">

                                            <input type="file"
                                                name="scan_ijazah"
                                                class="form-control"
                                                @if($isLocked) disabled @endif>

                                            <button type="submit"
                                                class="btn ml-3 btn-warning"
                                                @if($isLocked) disabled @endif>
                                                {{ isset($scan_ijazah) ? 'Ganti' : 'Unggah' }}
                                            </button>

                                            @if(isset($scan_ijazah))
                                            <a href="{{ Storage::url($scan_ijazah) }}"
                                                target="_blank"
                                                class="btn btn-outline-warning ml-2">
                                                Lihat
                                            </a>
                                            @endif

                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    @if(isset($scan_ijazah))
                                    <span class="text-success">✔ Sudah diunggah</span>
                                    @else
                                    <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- ================= KK ================= --}}
                            <tr>
                                <td>Scan KK</td>
                                <td>
                                    <form action="{{ route('pencaker.ak1.dokumen.upload', ['type' => 'scan_kk']) }}"
                                        method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex align-items-center">

                                            <input type="file"
                                                name="scan_kk"
                                                class="form-control"
                                                @if($isLocked) disabled @endif>

                                            <button type="submit"
                                                class="btn ml-3 btn-info"
                                                @if($isLocked) disabled @endif>
                                                {{ isset($scan_kk) ? 'Ganti' : 'Unggah' }}
                                            </button>

                                            @if(isset($scan_kk))
                                            <a href="{{ Storage::url($scan_kk) }}"
                                                target="_blank"
                                                class="btn btn-outline-info ml-2">
                                                Lihat
                                            </a>
                                            @endif

                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    @if(isset($scan_kk))
                                    <span class="text-success">✔ Sudah diunggah</span>
                                    @else
                                    <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>

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