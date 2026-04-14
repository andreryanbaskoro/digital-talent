@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="mb-0">{{ $title ?? 'Profil Pencari Kerja' }}</h1>
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

                            {{-- ================= HEADER PROFILE ================= --}}
                            <div class="text-center mb-4">

                                <div class="mb-2">
                                    @if($profil->foto)
                                    <img src="{{ asset('storage/'.$profil->foto) }}"
                                        class="rounded-circle shadow-sm"
                                        style="width:120px;height:120px;object-fit:cover;">
                                    @else
                                    <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                    @endif
                                </div>

                                <h4 class="mb-0">{{ $profil->nama_lengkap ?? '-' }}</h4>
                                <small class="text-muted">
                                    {{ $profil->kabupaten ?? '-' }}, {{ $profil->provinsi ?? '-' }}
                                </small>
                            </div>

                            <hr>

                            {{-- ================= DATA PRIBADI ================= --}}
                            <div class="row mb-2">
                                <div class="col-5 text-muted">ID Pencaker</div>
                                <div class="col-7">{{ $profil->id_pencari_kerja ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">NIK</div>
                                <div class="col-7">{{ $profil->nik ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">No KK</div>
                                <div class="col-7">{{ $profil->nomor_kk ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Tempat, Tanggal Lahir</div>
                                <div class="col-7">
                                    {{ $profil->tempat_lahir ?? '-' }},
                                    {{ $profil->tanggal_lahir ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d-m-Y') : '-' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Jenis Kelamin</div>
                                <div class="col-7">
                                    @if($profil->jenis_kelamin == 'L')
                                    Laki-laki
                                    @elseif($profil->jenis_kelamin == 'P')
                                    Perempuan
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Agama</div>
                                <div class="col-7">{{ $profil->agama ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Status Perkawinan</div>
                                <div class="col-7">{{ $profil->status_perkawinan ?? '-' }}</div>
                            </div>

                            <hr>

                            {{-- ================= ALAMAT ================= --}}
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Alamat</div>
                                <div class="col-7">{{ $profil->alamat ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">RT / RW</div>
                                <div class="col-7">
                                    {{ $profil->rt ?? '-' }} / {{ $profil->rw ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Kelurahan</div>
                                <div class="col-7">{{ $profil->kelurahan ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Kecamatan</div>
                                <div class="col-7">{{ $profil->kecamatan ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Kabupaten</div>
                                <div class="col-7">{{ $profil->kabupaten ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Provinsi</div>
                                <div class="col-7">{{ $profil->provinsi ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Kode Pos</div>
                                <div class="col-7">{{ $profil->kode_pos ?? '-' }}</div>
                            </div>

                            <hr>

                            {{-- ================= KONTAK ================= --}}
                            <div class="row mb-2">
                                <div class="col-5 text-muted">No HP</div>
                                <div class="col-7">{{ $profil->nomor_hp ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Email</div>
                                <div class="col-7">{{ $profil->email ?? '-' }}</div>
                            </div>

                            @else

                            <div class="alert alert-warning text-center">
                                Profil pencari kerja belum diisi.
                            </div>

                            @endif

                        </div>

                        {{-- ================= FOOTER ================= --}}
                        <div class="card-footer text-right">
                            <a href="{{ url('/profil/edit') }}"
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