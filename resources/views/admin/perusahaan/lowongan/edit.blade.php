@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title ?? 'Edit Lowongan' }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
             @include('admin.perusahaan.lowongan.partials.alerts')
            @include('admin.perusahaan.lowongan.partials._form')
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
@endpush