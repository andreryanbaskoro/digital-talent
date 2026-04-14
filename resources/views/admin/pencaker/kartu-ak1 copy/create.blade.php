@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title ?? 'Ajukan Kartu AK1' }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.pencaker.kartu-ak1.partials.alerts')

            <div class="card card-outline card-primary shadow-sm">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Form Pengajuan AK1</h3>
                </div>

                {{-- FORM --}}
                <form action="{{ url('/ak1') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- BODY --}}
                    <div class="card-body">

                        {{-- ID DETAIL (opsional, lebih jelas ke user) --}}
                        <div class="mb-3">
                            <label class="text-muted">ID Kartu AK1</label>
                            <div class="form-control bg-light font-weight-bold">
                                {{ $previewId ?? '-' }}
                            </div>
                            <small class="text-muted">
                                ID akan dibuat otomatis saat disimpan
                            </small>
                        </div>

                        @include('admin.pencaker.kartu-ak1.partials._form')

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer text-right">
                        <a href="{{ url('/ak1') }}" class="btn btn-secondary btn-kembali">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                        <button type="button" class="btn btn-submit btn-primary" data-action="save_all">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>

                    <!-- input hidden -->
                    <input type="hidden" name="action" id="formAction" value="save_all">


                </form>

            </div>

        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>

<script src="{{ asset('admin-js/pencaker-kartu-ak1.js') }}"></script>
@endpush