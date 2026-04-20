@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">
                    {{ $title ?? 'Lamar Pekerjaan' }}
                </h1>
            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.pencaker.lamaran-pekerjaan.partials.alerts')

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex align-items-start">

                        {{-- LOGO --}}
                        @php $perusahaan = $lowongan->profilPerusahaan ?? null; @endphp

                        @if($perusahaan && $perusahaan->logo)
                        <img src="{{ asset('storage/'.$perusahaan->logo) }}"
                            class="rounded mr-3"
                            style="width:70px; height:70px; object-fit:cover;">
                        @else
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded mr-3"
                            style="width:70px; height:70px;">
                            <strong>
                                {{ strtoupper(substr($perusahaan->nama_perusahaan ?? 'P', 0, 1)) }}
                            </strong>
                        </div>
                        @endif

                        {{-- INFO --}}
                        <div class="flex-grow-1">

                            <h5 class="mb-1 font-weight-bold">
                                {{ $lowongan->judul_lowongan }}
                            </h5>

                            <p class="text-muted mb-2">
                                {{ $perusahaan->nama_perusahaan ?? '-' }}
                            </p>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge badge-light border">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $lowongan->lokasi ?? '-' }}
                                </span>

                                <span class="badge badge-info">
                                    {{ $lowongan->jenis_pekerjaan ?? '-' }}
                                </span>

                                <span class="badge badge-success">
                                    {{ $lowongan->sistem_kerja ?? '-' }}
                                </span>

                            </div>

                        </div>

                        {{-- GAJI --}}
                        <div class="text-right ml-auto">
                            <small class="text-muted d-block">Gaji</small>
                            <strong class="text-success">
                                @if($lowongan->gaji_minimum && $lowongan->gaji_maksimum)
                                Rp {{ number_format($lowongan->gaji_minimum,0,',','.') }}
                                - Rp {{ number_format($lowongan->gaji_maksimum,0,',','.') }}
                                @elseif($lowongan->gaji_minimum)
                                Rp {{ number_format($lowongan->gaji_minimum,0,',','.') }}+
                                @else
                                Negosiasi
                                @endif
                            </strong>
                        </div>

                    </div>

                </div>
            </div>

            {{-- FORM --}}
            @include('admin.pencaker.lamaran-pekerjaan.partials._form', [
            'lowongan' => $lowongan,
            'previewId' => $previewId ?? null
            ])

        </div>
    </section>

</div>

@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/pencaker-lamaran.js') }}"></script>
@endpush