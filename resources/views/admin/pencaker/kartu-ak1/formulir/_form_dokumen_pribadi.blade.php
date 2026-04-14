@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Dokumen Pribadi' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @include('admin.pencaker.profil-pencaker.partials.alerts')

            {{-- Formulir Dokumen Pribadi --}}
            <div class="row justify-content-center">

                <div class="col-12">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Dokumen</th>
                                <th>Unggah & Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Foto Pas --}}
                            <tr>
                                <td>Foto Pas</td>
                                <td>
                                    <form action="{{ route('dokumen.upload', ['type' => 'foto_pas']) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex">
                                            <input type="file" name="foto_pas" class="form-control @error('foto_pas') is-invalid @enderror">
                                            @error('foto_pas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if(isset($foto_pas) && $foto_pas)
                                            <a href="{{ Storage::url($foto_pas) }}" target="_blank" class="btn btn-outline-primary ms-2">Lihat</a>
                                            @else
                                            <button type="submit" class="btn btn-primary ms-2">Unggah</button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @if(isset($foto_pas) && $foto_pas)
                                    <span class="text-success">Dokumen Foto Pas berhasil disimpan!</span>
                                    @else
                                    <span class="text-muted">Belum ada dokumen yang diunggah.</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Scan KTP --}}
                            <tr>
                                <td>Scan KTP</td>
                                <td>
                                    <form action="{{ route('dokumen.upload', ['type' => 'scan_ktp']) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex">
                                            <input type="file" name="scan_ktp" class="form-control @error('scan_ktp') is-invalid @enderror">
                                            @error('scan_ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if(isset($scan_ktp) && $scan_ktp)
                                            <a href="{{ Storage::url($scan_ktp) }}" target="_blank" class="btn btn-outline-success ms-2">Lihat</a>
                                            @else
                                            <button type="submit" class="btn btn-success ms-2">Unggah</button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @if(isset($scan_ktp) && $scan_ktp)
                                    <span class="text-success">Dokumen Scan KTP berhasil disimpan!</span>
                                    @else
                                    <span class="text-muted">Belum ada dokumen yang diunggah.</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Scan Ijazah --}}
                            <tr>
                                <td>Scan Ijazah</td>
                                <td>
                                    <form action="{{ route('dokumen.upload', ['type' => 'scan_ijazah']) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex">
                                            <input type="file" name="scan_ijazah" class="form-control @error('scan_ijazah') is-invalid @enderror">
                                            @error('scan_ijazah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if(isset($scan_ijazah) && $scan_ijazah)
                                            <a href="{{ Storage::url($scan_ijazah) }}" target="_blank" class="btn btn-outline-warning ms-2">Lihat</a>
                                            @else
                                            <button type="submit" class="btn btn-warning ms-2">Unggah</button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @if(isset($scan_ijazah) && $scan_ijazah)
                                    <span class="text-success">Dokumen Scan Ijazah berhasil disimpan!</span>
                                    @else
                                    <span class="text-muted">Belum ada dokumen yang diunggah.</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Scan KK --}}
                            <tr>
                                <td>Scan KK</td>
                                <td>
                                    <form action="{{ route('dokumen.upload', ['type' => 'scan_kk']) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex">
                                            <input type="file" name="scan_kk" class="form-control @error('scan_kk') is-invalid @enderror">
                                            @error('scan_kk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if(isset($scan_kk) && $scan_kk)
                                            <a href="{{ Storage::url($scan_kk) }}" target="_blank" class="btn btn-outline-info ms-2">Lihat</a>
                                            @else
                                            <button type="submit" class="btn btn-info ms-2">Unggah</button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @if(isset($scan_kk) && $scan_kk)
                                    <span class="text-success">Dokumen Scan KK berhasil disimpan!</span>
                                    @else
                                    <span class="text-muted">Belum ada dokumen yang diunggah.</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
@endpush