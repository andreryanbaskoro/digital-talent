@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h1 class="mb-2 mb-md-0">
                    {{ $title ?? 'Laporan Lowongan' }}
                </h1>

            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            {{-- DROPDOWN + STATISTIK --}}
            <div class="row mb-3">

                {{-- DROPDOWN --}}
                <div class="col-md-3 mb-3">

                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="w-100">

                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    Pilih Jenis Laporan
                                </label>

                                <div class="dropdown w-100">

                                    <button class="btn btn-light border dropdown-toggle w-100 text-left"
                                        type="button"
                                        id="laporanDropdown"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">

                                        @if(request()->routeIs('disnaker.laporan-lowongan.*'))
                                        <i class="fas fa-building mr-2"></i>
                                        Laporan Lowongan
                                        @else
                                        <i class="fas fa-search mr-2"></i>
                                        Laporan Pencari Kerja
                                        @endif

                                    </button>

                                    <div class="dropdown-menu w-100 shadow-sm"
                                        aria-labelledby="laporanDropdown">

                                        <a class="dropdown-item"
                                            href="{{ route('disnaker.laporan-lowongan.index') }}">

                                            <i class="fas fa-building mr-2 text-primary"></i>
                                            Laporan Lowongan

                                        </a>

                                        <a class="dropdown-item"
                                            href="{{ route('disnaker.laporan-pencari-kerja.index') }}">

                                            <i class="fas fa-search mr-2 text-success"></i>
                                            Laporan Pencari Kerja

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- CARD STATISTIK --}}
                <div class="col-md-9">

                    <div class="row">

                        @php
                        $cards = [
                        ['title' => 'Total', 'count' => $counts['all'], 'color' => 'info', 'icon' => 'briefcase'],
                        ['title' => 'Pending', 'count' => $counts['pending'], 'color' => 'warning', 'icon' => 'hourglass-half'],
                        ['title' => 'Disetujui', 'count' => $counts['disetujui'], 'color' => 'success', 'icon' => 'check-circle'],
                        ['title' => 'Terhapus', 'count' => $counts['deleted'], 'color' => 'danger', 'icon' => 'trash'],
                        ];
                        @endphp

                        @foreach($cards as $card)
                        <div class="col-md-3 col-6 mb-2">

                            <div class="small-box bg-{{ $card['color'] }}">

                                <div class="inner">
                                    <h3>{{ $card['count'] }}</h3>
                                    <p>{{ $card['title'] }}</p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-{{ $card['icon'] }}"></i>
                                </div>

                            </div>

                        </div>
                        @endforeach

                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="card card-primary card-outline shadow-sm">

                {{-- FILTER TAB --}}
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap">

                    {{-- LEFT: TABS --}}
                    <ul class="nav nav-tabs mb-2 mb-md-0">

                        @php
                        $tabs = [
                        'all' => 'Semua',
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'deleted' => 'Terhapus',
                        ];
                        @endphp

                        @foreach($tabs as $key => $label)
                        <li class="nav-item">
                            <a class="nav-link filter-tab {{ $key == 'all' ? 'active' : '' }}"
                                data-filter="{{ $key }}"
                                href="#">
                                {{ $label }}
                                <span class="badge badge-light ml-1">
                                    {{ $counts[$key] }}
                                </span>
                            </a>
                        </li>
                        @endforeach

                    </ul>

                    {{-- RIGHT: EXPORT BUTTON --}}
                    <div class="d-flex">

                        <a href="{{ route('disnaker.laporan-lowongan.excel') }}"
                            class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>

                        <a href="{{ route('disnaker.laporan-lowongan.pdf') }}"
                            class="btn btn-danger btn-sm mr-2">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>

                        <button type="button"
                            class="btn btn-secondary btn-sm mr-2"
                            onclick="openPrint(event)">
                            <i class="fas fa-print"></i> Cetak
                        </button>

                    </div>

                </div>

                {{-- TABLE --}}
                <div class="card-body pt-3">

                    @include('admin.disnaker.laporan-lowongan.partials.table', [
                    'lowongan' => $lowongan
                    ])

                </div>

            </div>

        </div>
    </section>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admin-js/disnaker-laporan-lowongan.js') }}"></script>

<script>
    let isPrinting = false;

    function openPrint(e) {
        e.preventDefault();

        if (isPrinting) return;
        isPrinting = true;

        const iframe = document.createElement('iframe');

        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.style.visibility = 'hidden';

        iframe.src = "{{ route('disnaker.laporan-lowongan.print') }}";

        iframe.onload = function() {
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                setTimeout(() => {
                    document.body.removeChild(iframe);
                    isPrinting = false;
                }, 1500);
            }, 400);
        };

        document.body.appendChild(iframe);
    }
</script>
@endpush