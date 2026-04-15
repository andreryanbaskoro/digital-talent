@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title ?? 'Edit Perusahaan' }}</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            {{-- Alert --}}
            @include('admin.disnaker.perusahaan.partials.alerts')

            {{-- Form --}}
            @include('admin.disnaker.perusahaan.partials._form', [
                'perusahaan' => $perusahaan
            ])

        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
@endpush