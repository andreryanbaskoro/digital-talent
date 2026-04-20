@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">{{ $title ?? 'Data Lowongan' }}</h1>

                <a href="{{ route('perusahaan.lowongan.create') }}"
                    class="btn btn-primary btn-sm shadow-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Tambah Lowongan
                </a>

            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            {{-- Alert --}}
            @include('admin.perusahaan.lowongan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <!-- TABS FILTER -->
                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="all" href="#">
                                    <i class="fas fa-list"></i> Semua
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="pending" href="#">
                                    Pending
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="disetujui" href="#">
                                    Disetujui
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="ditolak" href="#">
                                    Ditolak
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

                    @include('admin.perusahaan.lowongan.partials.table', [
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
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>

{{-- nanti kamu bisa buat js khusus --}}
<script src="{{ asset('admin-js/perusahaan-lowongan.js') }}"></script>
@endpush