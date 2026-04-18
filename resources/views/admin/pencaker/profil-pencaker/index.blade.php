@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="mb-0">{{ $title ?? 'Profil Pencari Kerja' }}</h1>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-9">

                    <div class="card card-outline card-primary">

                        {{-- HEADER PROFILE --}}
                        <div class="card-body text-center">

                            @if($profil)

                            <div class="mb-3">
                                @if($profil->foto)
                                <img src="{{ asset('storage/'.$profil->foto) }}"
                                    class="img-circle elevation-2"
                                    style="width:110px;height:110px;object-fit:cover;">
                                @else
                                <i class="fas fa-user-circle fa-6x text-secondary"></i>
                                @endif
                            </div>

                            <h4 class="mb-1">{{ $profil->nama_lengkap ?? '-' }}</h4>
                            <div class="text-muted">
                                {{ $profil->kab_kota ?? '-' }}, {{ $profil->provinsi ?? '-' }}
                            </div>

                            <hr>

                            {{-- GRID INFO --}}
                            <div class="row text-left">

                                {{-- LEFT --}}
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <small class="text-muted">ID Pencaker</small>
                                        <div class="font-weight-bold">{{ $profil->id_pencari_kerja ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">NIK</small>
                                        <div class="font-weight-bold">{{ $profil->nik ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">No KK</small>
                                        <div class="font-weight-bold">{{ $profil->nomor_kk ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">TTL</small>
                                        <div class="font-weight-bold">
                                            {{ $profil->tempat_lahir ?? '-' }},
                                            {{ $profil->tanggal_lahir ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Jenis Kelamin</small>
                                        <div class="font-weight-bold">
                                            {{ $profil->jenis_kelamin == 'L' ? 'Laki-laki' : ($profil->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                        </div>
                                    </div>

                                </div>

                                {{-- RIGHT --}}
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <small class="text-muted">Agama</small>
                                        <div class="font-weight-bold">{{ $profil->agama ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Status Perkawinan</small>
                                        <div class="font-weight-bold">{{ $profil->status_perkawinan ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Alamat</small>
                                        <div class="font-weight-bold">{{ $profil->alamat ?? '-' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Kelurahan / Kecamatan</small>
                                        <div class="font-weight-bold">
                                            {{ $profil->kelurahan ?? '-' }} / {{ $profil->kecamatan ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">RT / RW / Kode Pos</small>
                                        <div class="font-weight-bold">
                                            {{ $profil->rt ?? '-' }} / {{ $profil->rw ?? '-' }} / {{ $profil->kode_pos ?? '-' }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <hr>

                            {{-- CONTACT --}}
                            <div class="row text-left">

                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">No HP</small>
                                    <div class="font-weight-bold">{{ $profil->nomor_hp ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Email</small>
                                    <div class="font-weight-bold">{{ $profil->email ?? '-' }}</div>
                                </div>

                            </div>

                            @else

                            <div class="alert alert-warning mb-0">
                                Profil pencari kerja belum diisi.
                            </div>

                            @endif

                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer text-right">
                            <a href="{{ route('pencaker.profil.edit') }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Profil
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>
@endsection