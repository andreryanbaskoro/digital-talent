@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">{{ $title ?? 'Data Pencari Kerja' }}</h1>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            {{-- Alert --}}
            @include('admin.disnaker.pencari-kerja.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <!-- TABS -->
                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="all" href="#">
                                    <i class="fas fa-list"></i> Semua
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="deleted" href="#">
                                    <i class="fas fa-trash text-danger"></i> Terhapus
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body pt-3">

                    {{-- Filter Data --}}
                    <div id="filter-data">
                        <div class="row">
                            <!-- Search, Filter Options -->
                        </div>
                    </div>

                    @include('admin.disnaker.pencari-kerja.partials.table', [
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
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/disnaker-pencari-kerja.js') }}"></script>
@endpush