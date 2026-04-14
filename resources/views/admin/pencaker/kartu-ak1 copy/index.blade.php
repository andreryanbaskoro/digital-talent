@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Kartu AK1' }}</h1>

            <a href="{{ url('/ak1/create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Ajukan AK1
            </a>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            {{-- ================= PROFIL RINGKAS ================= --}}
            @if($profil)
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center">

                    <div class="mr-3">
                        @if($profil->foto)
                        <img src="{{ asset('storage/'.$profil->foto) }}"
                            class="rounded-circle"
                            style="width:70px;height:70px;object-fit:cover;">
                        @else
                        <i class="fas fa-user-circle fa-4x text-secondary"></i>
                        @endif
                    </div>

                    <div>
                        <h5 class="mb-0">{{ $profil->nama_lengkap }}</h5>
                        <small class="text-muted">
                            {{ $profil->kabupaten }}, {{ $profil->provinsi }}
                        </small>
                    </div>

                </div>
            </div>
            @endif

            {{-- ================= LIST AK1 ================= --}}
            <div class="row">

                @forelse($daftarAk1 as $item)

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm mb-4">

                        <div class="card-body">

                            {{-- ID --}}
                            <h5 class="mb-2">{{ $item->id_kartu_ak1 }}</h5>

                            {{-- STATUS --}}
                            @php
                            $badge = match($item->status) {
                            'draft' => 'secondary',
                            'pending' => 'warning',
                            'diproses' => 'info',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            default => 'dark'
                            };
                            @endphp

                            <span class="badge badge-{{ $badge }}">
                                {{ strtoupper($item->status) }}
                            </span>

                            {{-- TANGGAL --}}
                            <p class="mt-2 mb-1 text-muted">
                                Tanggal:
                                {{ $item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d-m-Y') : '-' }}
                            </p>

                            {{-- BERLAKU --}}
                            @if($item->berlaku_mulai && $item->berlaku_sampai)
                            <p class="mb-1 text-muted">
                                Berlaku:
                                {{ \Carbon\Carbon::parse($item->berlaku_mulai)->format('d-m-Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->berlaku_sampai)->format('d-m-Y') }}
                            </p>
                            @endif

                        </div>

                        {{-- ACTION --}}
                        <div class="card-footer d-flex justify-content-between">

                            <a href="{{ url('/ak1/'.$item->id_kartu_ak1) }}"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if(in_array($item->status, ['draft','ditolak']))

                            <a href="{{ url('/ak1/'.$item->id_kartu_ak1.'/edit') }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ url('/ak1/'.$item->id_kartu_ak1.'/submit') }}"
                                method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm"
                                    onclick="return confirm('Ajukan AK1 sekarang?')">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>

                            @endif

                        </div>

                    </div>
                </div>

                @empty

                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada pengajuan AK1.
                    </div>
                </div>

                @endforelse

            </div>

        </div>
    </section>

</div>
@endsection