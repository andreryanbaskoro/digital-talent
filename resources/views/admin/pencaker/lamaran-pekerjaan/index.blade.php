@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">
                    {{ $title ?? 'Data Lamaran Pekerjaan' }}
                </h1>
                
                <a href="{{ url('/') }}#lowongan"
                    class="btn btn-primary btn-sm shadow-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Lamar Pekerjaan
                </a>

            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.pencaker.lamaran-pekerjaan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER CARD -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        {{-- TABS FILTER --}}
                        <ul class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link filter-tab active" data-filter="all" href="#">
                                    <i class="fas fa-list"></i> Semua
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="dikirim" href="#">
                                    <i class="fas fa-paper-plane text-primary"></i> Dikirim
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="diproses" href="#">
                                    <i class="fas fa-spinner text-warning"></i> Diproses
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="diterima" href="#">
                                    <i class="fas fa-check text-success"></i> Diterima
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="ditolak" href="#">
                                    <i class="fas fa-times text-danger"></i> Ditolak
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body pt-3">

                    @include('admin.pencaker.lamaran-pekerjaan.partials.table', [
                    'lamaran' => $lamaran
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
<script src="{{ asset('admin-js/pencaker-lamaran.js') }}"></script>
@endpush