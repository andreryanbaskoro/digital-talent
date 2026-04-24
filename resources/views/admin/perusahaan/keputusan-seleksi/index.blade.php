@extends('layouts.app-admin')

@section('title', $title ?? 'Keputusan Seleksi')

@section('content')
<div class="content-wrapper">

    <section class="content-header mb-4">
        <div class="container-fluid">

            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

                    <div class="mb-2 mb-md-0">
                        <h3 class="mb-1 font-weight-bold">
                            Keputusan Seleksi
                        </h3>
                        <small class="text-muted">
                            Pilih lowongan untuk memberi keputusan final pada kandidat berdasarkan hasil ranking.
                        </small>
                    </div>

                    <div>
                        <span class="badge bg-primary px-3 py-2">
                            {{ count($lowongan) }} Lowongan
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @include('admin.perusahaan.lowongan.partials.alerts')

            <div class="row">
                @forelse($lowongan as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3">
                        <div class="card-body d-flex flex-column p-4">

                            <div class="mb-2">
                                <h5 class="font-weight-bold mb-1">
                                    {{ $item->judul_lowongan ?? '-' }}
                                </h5>
                                <div class="text-muted small">
                                    {{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Jumlah Lamaran</span>
                                    <span class="badge bg-info text-white px-3 py-2">
                                        {{ $item->lamaran_count ?? $item->jumlah_lamaran ?? 0 }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Sudah Dihitung</span>
                                    <span class="badge {{ ($item->sudah_dihitung ?? false) ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                        {{ ($item->sudah_dihitung ?? false) ? 'Ya' : 'Belum' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Diproses</span>
                                    <span class="badge bg-warning px-3 py-2">
                                        {{ $item->jumlah_diproses ?? 0 }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Diterima</span>
                                    <span class="badge bg-success px-3 py-2">
                                        {{ $item->jumlah_diterima ?? 0 }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Ditolak</span>
                                    <span class="badge bg-dark px-3 py-2">
                                        {{ $item->jumlah_ditolak ?? 0 }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('perusahaan.keputusan-seleksi.show', $item->id_lowongan) }}"
                                    class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-check-circle mr-1"></i> Buka Keputusan Seleksi
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <h5 class="text-muted mb-2">Belum Ada Lowongan</h5>
                            <small class="text-muted">
                                Silakan buat lowongan terlebih dahulu untuk melakukan keputusan seleksi.
                            </small>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

        </div>
    </section>

</div>
@endsection