@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0">
                    {{ $title ?? 'Laporan Pencari Kerja' }}
                </h1>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            @php
            $cards = [
            [
            'title' => 'Total',
            'count' => $counts['all'],
            'color' => 'info',
            'icon' => 'users'
            ],
            [
            'title' => 'Aktif',
            'count' => $counts['aktif'],
            'color' => 'primary',
            'icon' => 'user-check'
            ],
            [
            'title' => 'Terhapus',
            'count' => $counts['deleted'],
            'color' => 'danger',
            'icon' => 'trash'
            ],
            ];
            @endphp

            {{-- DROPDOWN + STATISTIK --}}
            <div class="row">

                {{-- DROPDOWN --}}
                <div class="col-md-3 mb-4">

                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="w-100">

                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    Pilih Jenis Laporan
                                </label>

                                <select id="laporanSelect" class="form-control">

                                    <option value="{{ route('disnaker.laporan-lowongan.index') }}"
                                        {{ request()->routeIs('disnaker.laporan-lowongan.*') ? 'selected' : '' }}>
                                        🏢 Laporan Lowongan
                                    </option>

                                    <option value="{{ route('disnaker.laporan-pencari-kerja.index') }}"
                                        {{ request()->routeIs('disnaker.laporan-pencari-kerja.*') ? 'selected' : '' }}>
                                        🔍 Laporan Pencari Kerja
                                    </option>

                                </select>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- CARD STATISTIK --}}
                <div class="col-md-9">

                    <div class="row row-cols-1 row-cols-md-3">

                        @foreach($cards as $card)
                        <div class="col mb-4">

                            <div class="small-box bg-{{ $card['color'] }} h-100 d-flex flex-column justify-content-between">

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

            {{-- CARD TABLE --}}
            <div class="card card-primary card-outline shadow-sm">

                {{-- FILTER + EXPORT --}}
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap">

                    {{-- TABS --}}
                    <ul class="nav nav-tabs mb-2 mb-md-0">

                        @php
                        $tabs = [
                        'all' => 'Semua',
                        'aktif' => 'Aktif',
                        'punya_ak1' => 'Punya AK1',
                        'punya_lamaran' => 'Melamar',
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

                    {{-- EXPORT --}}
                    <div class="d-flex flex-wrap">

                        <a href="{{ route('disnaker.laporan-pencari-kerja.excel') }}"
                            class="btn btn-success btn-sm mr-2 mb-2">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>

                        <a href="{{ route('disnaker.laporan-pencari-kerja.pdf') }}"
                            class="btn btn-danger btn-sm mr-2 mb-2">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>

                        <button type="button"
                            class="btn btn-secondary btn-sm mr-2 mb-2 btn-print">
                            <i class="fas fa-print"></i> Cetak
                        </button>

                    </div>

                </div>

                {{-- TABLE --}}
                <div class="card-body pt-3">

                    @include('admin.disnaker.laporan-pencari-kerja.partials.table', [
                    'pencariKerja' => $pencariKerja
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

<script>
    document.getElementById('laporanSelect')
        .addEventListener('change', function() {
            window.location.href = this.value;
        });
</script>


@endpush