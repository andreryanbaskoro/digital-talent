@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Formulir AK1' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            {{-- ================= FORMULIR AK1 ================= --}}
            <div class="row justify-content-center">

                @include('admin.pencaker.kartu-ak1.formulir.index_profil')
                @include('admin.pencaker.kartu-ak1.formulir.index_dokumen_pribadi')
                @include('admin.pencaker.kartu-ak1.formulir.index_riwayat_pendidikan')
                @include('admin.pencaker.kartu-ak1.formulir.index_keterampilan')
                @include('admin.pencaker.kartu-ak1.formulir.index_pengalaman')



            </div>

        </div>
    </section>
</div>
@endsection