@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">
                    {{ $title ?? 'Edit Lamaran Pekerjaan' }}
                </h1>

            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.pencaker.lamaran-pekerjaan.partials.alerts')

            {{-- FORM --}}
            @include('admin.pencaker.lamaran-pekerjaan.partials._form', [
            'lamaran' => $lamaran
            ])

        </div>
    </section>

</div>

@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/pencaker-lamaran.js') }}"></script>
@endpush