@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="mb-0">{{ $title ?? 'Detail Pengguna' }}</h1>


            </div>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.disnaker.pengguna.partials.alerts')

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card card-outline card-info shadow-sm">

                        {{-- BODY --}}
                        <div class="card-body">

                            {{-- PROFILE STYLE --}}
                            <div class="text-center mb-4">
                                <div class="mb-2">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h4 class="mb-0">{{ $pengguna->nama }}</h4>
                                <small class="text-muted">{{ $pengguna->email }}</small>
                            </div>

                            <hr>

                            {{-- DETAIL LIST --}}
                            <div class="row mb-3">
                                <div class="col-5 text-muted">Peran</div>
                                <div class="col-7 font-weight-bold">
                                    {{ ucfirst(str_replace('_',' ', $pengguna->peran)) }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">Status</div>
                                <div class="col-7">
                                    @if($pengguna->deleted_at)
                                    <span class="badge badge-danger px-3 py-2">
                                        Terhapus
                                    </span>
                                    @elseif($pengguna->status == 'aktif')
                                    <span class="badge badge-success px-3 py-2">
                                        Aktif
                                    </span>
                                    @elseif($pengguna->status == 'nonaktif')
                                    <span class="badge badge-secondary px-3 py-2">
                                        Nonaktif
                                    </span>
                                    @else
                                    <span class="badge badge-light px-3 py-2">
                                        {{ ucfirst($pengguna->status) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-5 text-muted">Dibuat Pada</div>
                                <div class="col-7">
                                    {{ $pengguna->created_at->format('d M Y H:i') }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-5 text-muted">Terakhir Diubah</div>
                                <div class="col-7">
                                    {{ $pengguna->updated_at->format('d M Y H:i') }}
                                </div>
                            </div>

                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer d-flex align-items-center">

                            {{-- Kiri --}}
                            <a href="{{ route('disnaker.pengguna.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>

                            {{-- Kanan --}}
                            <div class="ml-auto d-flex align-items-center">

                                {{-- Jika terhapus (soft delete) --}}
                                @if($pengguna->trashed())
                                <form action="{{ route('pengguna.restore', $pengguna->id_pengguna) }}" method="POST" class="d-inline mr-2">
                                    @csrf
                                    <button type="button"
                                        class="btn btn-success btn-sm btn-restore"
                                        data-toggle="tooltip"
                                        title="Pulihkan Data"
                                        data-url="{{ route('pengguna.restore', $pengguna->id_pengguna) }}">
                                        <i class="fas fa-undo mr-1"></i> Pulihkan
                                    </button>
                                </form>
                                @endif

                                {{-- Tombol Edit --}}
                                <a href="{{ route('disnaker.pengguna.edit', $pengguna->id_pengguna) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Pengguna
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