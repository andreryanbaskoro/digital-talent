@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">{{ $title ?? 'Data Lamaran Pekerjaan' }}</h1>

            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.perusahaan.lamaran-pekerjaan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER TABS -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link filter-tab active" data-filter="all" href="#">
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

                    @include('admin.perusahaan.lamaran-pekerjaan.partials.table', [
                    'lamaran' => $lamaran
                    ])

                </div>

            </div>

        </div>
    </section>

</div>

@include('admin.perusahaan.lamaran-pekerjaan.show')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/perusahaan-lamaran.js') }}"></script>
@endpush