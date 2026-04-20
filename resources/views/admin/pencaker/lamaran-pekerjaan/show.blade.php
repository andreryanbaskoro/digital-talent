@extends('layouts.app-admin')

@push('styles')
<style>
    .card-body h4 {
        font-size: 20px;
    }

    .badge {
        font-size: 13px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="mb-0">{{ $title ?? 'Detail Lamaran' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.pencaker.lamaran-pekerjaan.partials.alerts')

            <div class="row">
                <div class="col-md-10 mx-auto">

                    {{-- ================= CARD UTAMA ================= --}}
                    <div class="card shadow-sm border-0">

                        {{-- ================= HEADER CARD ================= --}}
                        <div class="card-body border-bottom">

                            <div class="row align-items-center">

                                {{-- ================= LOGO PERUSAHAAN ================= --}}
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    @if(optional($lamaran->lowongan->profilPerusahaan)->logo)
                                    <img src="{{ asset('storage/' . $lamaran->lowongan->profilPerusahaan->logo) }}"
                                        alt="Logo"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 80px;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                        style="height:80px;">
                                        <i class="fas fa-building fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                </div>

                                {{-- ================= INFO LOWONGAN ================= --}}
                                <div class="col-md-7">

                                    <h4 class="font-weight-bold mb-1">
                                        {{ $lamaran->lowongan->judul_lowongan ?? '-' }}
                                    </h4>

                                    <div class="text-muted mb-2">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $lamaran->lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}
                                    </div>

                                    <div class="small text-muted">

                                        <span class="mr-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $lamaran->lowongan->lokasi ?? '-' }}
                                        </span>

                                        <span class="mr-3">
                                            <i class="fas fa-briefcase"></i>
                                            {{ $lamaran->lowongan->jenis_pekerjaan ?? '-' }}
                                        </span>

                                        <span class="mr-3">
                                            <i class="fas fa-laptop-house"></i>
                                            {{ $lamaran->lowongan->sistem_kerja ?? '-' }}
                                        </span>

                                        @if($lamaran->lowongan->gaji_minimum || $lamaran->lowongan->gaji_maksimum)
                                        <span>
                                            <i class="fas fa-money-bill-wave"></i>
                                            Rp{{ number_format($lamaran->lowongan->gaji_minimum,0,',','.') }}
                                            -
                                            Rp{{ number_format($lamaran->lowongan->gaji_maksimum,0,',','.') }}
                                        </span>
                                        @endif

                                    </div>
                                </div>

                                {{-- ================= STATUS ================= --}}
                                <div class="col-md-3 text-md-right text-center mt-3 mt-md-0">

                                    @php
                                    $status = $lamaran->status_lamaran;
                                    $color = match($status) {
                                    'diterima' => 'success',
                                    'ditolak' => 'danger',
                                    'diproses' => 'warning',
                                    'dikirim' => 'primary',
                                    default => 'secondary'
                                    };
                                    @endphp

                                    <span class="badge badge-{{ $color }} px-4 py-2 mb-2 d-inline-block">
                                        {{ strtoupper($status) }}
                                    </span>

                                    <div class="text-muted small">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($lamaran->tanggal_lamar)->format('d M Y') }}
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ================= INFO GRID ================= --}}
                        <div class="card-body">

                            <div class="row text-sm">

                                <div class="col-md-6 mb-3">
                                    <div class="text-muted">ID Lamaran</div>
                                    <code class="font-weight-bold">
                                        {{ $lamaran->id_lamaran }}
                                    </code>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="text-muted">Tanggal Lamar</div>
                                    <div>
                                        {{ \Carbon\Carbon::parse($lamaran->tanggal_lamar)->format('d M Y H:i') }}
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- ================= SKILL ================= --}}
                        <div class="card-body border-top">

                            <h5 class="mb-3">
                                <i class="fas fa-brain text-primary mr-1"></i>
                                Penilaian Skill
                            </h5>

                            @php
                            $grouped = $lamaran->subKriteriaLamaran->groupBy(function($item){
                            return optional($item->subKriteria->kriteria)->nama_kriteria ?? 'Lainnya';
                            });
                            @endphp

                            @forelse($grouped as $kriteria => $items)
                            <div class="mb-4">

                                <h6 class="text-primary font-weight-bold mb-2">
                                    {{ $kriteria }}
                                </h6>

                                <div class="row">

                                    @foreach($items as $item)
                                    <div class="col-md-6 mb-2">

                                        <div class="d-flex justify-content-between border rounded px-3 py-2">

                                            <div>
                                                {{ $item->subKriteria->nama_sub_kriteria ?? '-' }}
                                            </div>

                                            {{-- STAR RATING --}}
                                            <div class="text-warning">
                                                @for($i=1; $i<=5; $i++)
                                                    <i class="fas fa-star {{ $i <= $item->nilai ? '' : 'text-secondary' }}"></i>
                                                    @endfor
                                            </div>

                                        </div>

                                    </div>
                                    @endforeach

                                </div>

                            </div>
                            @empty
                            <div class="text-muted text-center">
                                Tidak ada data skill
                            </div>
                            @endforelse

                        </div>

                        {{-- ================= DOKUMEN ================= --}}
                        <div class="card-body border-top">

                            <h5 class="mb-3">
                                <i class="fas fa-folder-open text-primary mr-1"></i>
                                Dokumen Lamaran
                            </h5>

                            <div class="row">

                                @forelse($lamaran->dokumen as $dok)
                                <div class="col-md-4 mb-3">

                                    <div class="card border shadow-sm h-100">

                                        <div class="card-body text-center">

                                            <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>

                                            <div class="font-weight-bold mb-1">
                                                {{ strtoupper($dok->jenis_dokumen) }}
                                            </div>

                                            <a href="{{ asset('storage/'.$dok->lokasi_file) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>

                                        </div>

                                    </div>

                                </div>
                                @empty
                                <div class="col-12 text-center text-muted">
                                    Tidak ada dokumen
                                </div>
                                @endforelse

                            </div>

                        </div>

                        {{-- ================= FOOTER ================= --}}
                        <div class="card-footer d-flex">



                            @php
                            $lowongan = $lamaran->lowongan;
                            $now = now();

                            $isExpired = $lowongan->tanggal_berakhir
                            ? \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->lt($now)
                            : false;

                            $isNotStarted = $lowongan->tanggal_mulai
                            ? \Carbon\Carbon::parse($lowongan->tanggal_mulai)->gt($now)
                            : false;

                            $canModify = !$isExpired && !$isNotStarted;
                            @endphp

                            <div class="ml-auto">

                                @if($canModify)

                                <a href="{{ route('pencaker.lamaran.edit', $lamaran->id_lamaran) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>


                                @else


                                <a href="{{ route('pencaker.lamaran.index') }}"
                                    class="btn btn-outline-secondary btn-sm btn-kembali">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>

                                <span class="badge badge-secondary px-3 py-2">
                                    Tidak dapat diubah
                                </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>
@endsection