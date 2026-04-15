@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center mb-2">

                <!-- TITLE -->
                <div>
                    <h1 class="mb-0">{{ $title ?? 'Data AK1' }}</h1>
                </div>

            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            @include('admin.disnaker.ak1.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- FILTER -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <ul class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link filter-tab active" data-filter="all" href="#">
                                    <i class="fas fa-list"></i> Semua
                                    <span class="badge badge-light ml-1">{{ $counts['total'] ?? 0 }}</span> <!-- Updated this to reflect active records only -->
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="draft" href="#">
                                    <i class="fas fa-file-alt text-secondary"></i> Draft
                                    <span class="badge badge-light ml-1">{{ $counts['draft'] ?? 0 }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="pending" href="#">
                                    <i class="fas fa-clock text-warning"></i> Pending
                                    <span class="badge badge-light ml-1">{{ $counts['pending'] ?? 0 }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="disetujui" href="#">
                                    <i class="fas fa-check-circle text-success"></i> Disetujui
                                    <span class="badge badge-light ml-1">{{ $counts['disetujui'] ?? 0 }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="ditolak" href="#">
                                    <i class="fas fa-times-circle text-danger"></i> Ditolak
                                    <span class="badge badge-light ml-1">{{ $counts['ditolak'] ?? 0 }}</span>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="card-body pt-3">

                    @include('admin.disnaker.ak1.partials.table')
                </div>

            </div>

        </div>

    </section>

</div>

@include('admin.disnaker.ak1.modal')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/disnaker-ak1.js') }}"></script>
@endpush