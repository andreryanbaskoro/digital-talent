@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="mb-0">{{ $title ?? 'Profil Perusahaan' }}</h1>
            </div>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card card-outline card-primary shadow-sm">

                        {{-- BODY --}}
                        <div class="card-body">

                            @if($profil)

                            {{-- PROFILE HEADER --}}
                            <div class="text-center mb-4">
                                <div class="mb-2">
                                    @if($profil->logo)
                                    <img src="{{ asset('storage/'.$profil->logo) }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width:120px; height:120px; object-fit:cover;">
                                    @else
                                    <i class="fas fa-building fa-4x text-secondary"></i>
                                    @endif
                                </div>

                                <h4 class="mb-0">{{ $profil->nama_perusahaan }}</h4>
                                <small class="text-muted">
                                    {{ $profil->kab_kota }}, {{ $profil->provinsi }}
                                </small>
                            </div>

                            <hr>

                            {{-- DETAIL LIST --}}
                            <div class="row mb-3">
                                <div class="col-5 text-muted">NIB</div>
                                <div class="col-7 font-weight-bold">
                                    {{ $profil->nib ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">NPWP</div>
                                <div class="col-7">
                                    {{ $profil->npwp ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">Alamat</div>
                                <div class="col-7">
                                    {{ $profil->alamat ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">No Telepon</div>
                                <div class="col-7">
                                    {{ $profil->nomor_telepon ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">Website</div>
                                <div class="col-7">
                                    @if($profil->website)
                                    <a href="{{ $profil->website }}" target="_blank">
                                        {{ $profil->website }}
                                    </a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- DESKRIPSI --}}
                            <div class="mb-2 text-muted">Deskripsi Perusahaan</div>
                            <div>
                                {{ $profil->deskripsi ?? 'Belum ada deskripsi perusahaan.' }}
                            </div>

                            @else
                            <div class="alert alert-warning text-center">
                                Profil perusahaan belum diisi.
                            </div>
                            @endif

                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer d-flex align-items-center">

                            {{-- KIRI --}}

                            {{-- KANAN --}}
                            <div class="ml-auto">
                                <a href="{{ url('admin/perusahaan/profil/edit') }}">
                                    class="btn btn-primary btn-submit btn-sm">
                                    <i class="fas fa-edit"></i> Edit Profil
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>
@endsection