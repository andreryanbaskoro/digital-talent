@extends('layouts.app-admin')

@section('title', $title ?? 'Keputusan Seleksi')

@section('content')
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h1 class="mb-0">Keputusan Seleksi</h1>
                    <small class="text-muted">
                        {{ $lowongan->judul_lowongan ?? '-' }} -
                        {{ $lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}
                    </small>
                </div>

                <a href="{{ route('perusahaan.keputusan-seleksi.index') }}"
                    class="btn btn-outline-secondary">
                    ← Kembali
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @include('admin.perusahaan.lowongan.partials.alerts')

            <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <div class="text-muted">Total Lamaran</div>
                            <h3 class="mb-0">{{ $summary['total'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <div class="text-muted">Diproses</div>
                            <h3 class="mb-0">{{ $summary['diproses'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <div class="text-muted">Diterima</div>
                            <h3 class="mb-0 text-success">{{ $summary['diterima'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <div class="text-muted">Ditolak</div>
                            <h3 class="mb-0 text-dark">{{ $summary['ditolak'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <small class="text-muted">
                        Keputusan akhir ditentukan berdasarkan peringkat sistem dan verifikasi administrator
                        dengan status <b class="text-success">Diterima</b> atau <b class="text-danger">Ditolak</b>.
                    </small>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    @include('admin.perusahaan.keputusan-seleksi.partials.table', [
                    'lamaran' => $lamaran,
                    'lowongan' => $lowongan
                    ])
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Keputusan -->
<div class="modal fade" id="modalKeputusan" tabindex="-1" role="dialog" aria-labelledby="modalKeputusanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formKeputusan" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKeputusanLabel">Keputusan Seleksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <div id="modalKeputusanSubtitle">Isi catatan keputusan final.</div>
                    </div>

                    <div class="form-group">
                        <label for="catatan_perusahaan">Catatan Keputusan</label>
                        <textarea
                            name="catatan_perusahaan"
                            id="catatan_perusahaan"
                            class="form-control @error('catatan_perusahaan') is-invalid @enderror"
                            rows="5"
                            required
                            placeholder="Wajib isi catatan keputusan final..."></textarea>

                        @error('catatan_perusahaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitKeputusan">
                        Simpan Keputusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = $('#modalKeputusan');
        const form = document.getElementById('formKeputusan');
        const titleEl = document.getElementById('modalKeputusanLabel');
        const subtitleEl = document.getElementById('modalKeputusanSubtitle');
        const catatanEl = document.getElementById('catatan_perusahaan');
        const submitBtn = document.getElementById('btnSubmitKeputusan');

        document.querySelectorAll('.btn-keputusan').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const title = this.dataset.title || 'Keputusan Seleksi';
                const subtitle = this.dataset.subtitle || 'Isi catatan keputusan final.';
                const status = this.dataset.status || '';

                form.action = action;
                titleEl.textContent = title;
                subtitleEl.textContent = subtitle;
                catatanEl.value = '';

                if (status === 'diterima') {
                    submitBtn.className = 'btn btn-success';
                    submitBtn.textContent = 'Simpan Keputusan Diterima';
                } else if (status === 'ditolak') {
                    submitBtn.className = 'btn btn-dark';
                    submitBtn.textContent = 'Simpan Keputusan Ditolak';
                } else {
                    submitBtn.className = 'btn btn-primary';
                    submitBtn.textContent = 'Simpan Keputusan';
                }

                modal.modal('show');
            });
        });
    });
</script>
@endpush