@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h1 class="mb-2 mb-md-0">
                    {{ $title ?? 'Laporan Data Pelamar Perusahaan' }}
                </h1>

            </div>

        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            @php
            $total = $data->count();

            $laki = $data->filter(function($item){
            return optional($item->pencariKerja)->jenis_kelamin == 'L';
            })->count();

            $perempuan = $data->filter(function($item){
            return optional($item->pencariKerja)->jenis_kelamin == 'P';
            })->count();

            $cards = [
            [
            'title' => 'Total Pelamar',
            'count' => $total,
            'color' => 'info',
            'icon' => 'users'
            ],
            [
            'title' => 'Laki-laki',
            'count' => $laki,
            'color' => 'primary',
            'icon' => 'male'
            ],
            [
            'title' => 'Perempuan',
            'count' => $perempuan,
            'color' => 'danger',
            'icon' => 'female'
            ],
            ];
            @endphp

            {{-- DROPDOWN + STATISTIK --}}
            <div class="row">

                {{-- DROPDOWN JENIS LAPORAN --}}
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="w-100">
                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    Pilih Jenis Laporan
                                </label>
                                <select id="laporanSelect" class="form-control">
                                    <option value="{{ route('laporan.pelamar-perusahaan.index', ['mode' => $mode]) }}" selected>
                                        🏢 Laporan Data Pelamar Perusahaan
                                    </option>
                                    <option value="{{ route('laporan.pencari-kerja.index', ['mode' => $mode]) }}">
                                        🔍 Laporan Data Pencari Kerja
                                    </option>
                                    <option
                                        value="{{ route('laporan.profile-matching.index', ['mode' => $mode]) }}">
                                        📊 Laporan Rekapitulasi Profile Matching
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD STATISTIK --}}
                <div class="col-md-9">

                    <div class="row row-cols-1 row-cols-md-3">

                        @foreach($cards as $card)
                        <div class="col mb-4">

                            <div class="small-box bg-{{ $card['color'] }} h-100 d-flex flex-column justify-content-between">

                                <div class="inner">
                                    <h3>{{ $card['count'] }}</h3>
                                    <p>{{ $card['title'] }}</p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-{{ $card['icon'] }}"></i>
                                </div>

                            </div>

                        </div>
                        @endforeach

                    </div>

                </div>

            </div>

            {{-- CARD TABLE --}}
            <div class="card card-primary card-outline shadow-sm">

                {{-- FILTER + EXPORT --}}
                <div class="card-header border-0 pb-0">

                    {{-- FILTER --}}
                    <form method="GET"
                        action="{{ route('laporan.pelamar-perusahaan.index', ['mode' => $mode]) }}">

                        <div class="row">

                            {{-- DISNAKER ONLY --}}
                            @if($mode == 'disnaker')
                            <div class="col-md-3 mb-3">

                                <label>Nama Perusahaan</label>

                                <select name="nama_perusahaan" class="form-control">

                                    <option value="">-- Semua Perusahaan --</option>

                                    @foreach($perusahaan as $item)
                                    <option
                                        value="{{ $item->nama_perusahaan }}"
                                        {{ request('nama_perusahaan') == $item->nama_perusahaan ? 'selected' : '' }}>

                                        {{ $item->nama_perusahaan }}

                                    </option>
                                    @endforeach

                                </select>

                            </div>
                            @endif

                            {{-- JENIS PEKERJAAN --}}
                            <div class="col-md-3 mb-3">

                                <label>Jenis Pekerjaan</label>

                                <select name="jenis_pekerjaan" class="form-control">

                                    <option value="">-- Semua Jenis --</option>

                                    @foreach($jenisPekerjaan as $item)
                                    <option
                                        value="{{ $item->jenis_pekerjaan }}"
                                        {{ request('jenis_pekerjaan') == $item->jenis_pekerjaan ? 'selected' : '' }}>

                                        {{ $item->jenis_pekerjaan }}

                                    </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- NAMA PEKERJAAN --}}
                            <div class="col-md-3 mb-3">

                                <label>Nama Pekerjaan</label>

                                <select name="nama_pekerjaan" class="form-control">

                                    <option value="">-- Semua Pekerjaan --</option>

                                    @foreach($namaPekerjaan as $item)
                                    <option
                                        value="{{ $item->judul_lowongan }}"
                                        {{ request('nama_pekerjaan') == $item->judul_lowongan ? 'selected' : '' }}>

                                        {{ $item->judul_lowongan }}

                                    </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- TANGGAL POSTING --}}
                            <div class="col-md-3 mb-3">

                                <label>Tanggal Posting</label>

                                <input type="date"
                                    name="tanggal_posting"
                                    class="form-control"
                                    value="{{ request('tanggal_posting') }}">

                            </div>

                            {{-- PERUSAHAAN ONLY --}}
                            @if($mode == 'perusahaan')
                            <div class="col-md-3 mb-3">

                                <label>Jenis Kelamin</label>

                                <select name="jenis_kelamin" class="form-control">

                                    <option value="">-- Semua --</option>

                                    @foreach($jenisKelamin as $item)
                                    <option
                                        value="{{ $item->jenis_kelamin }}"
                                        {{ request('jenis_kelamin') == $item->jenis_kelamin ? 'selected' : '' }}>

                                        {{ $item->jenis_kelamin }}

                                    </option>
                                    @endforeach

                                </select>

                            </div>
                            @endif

                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center">

                            <div class="mb-3">

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>

                                <a href="{{ route('laporan.pelamar-perusahaan.index', ['mode' => $mode]) }}"
                                    class="btn btn-secondary btn-sm">

                                    <i class="fas fa-sync"></i> Reset

                                </a>

                            </div>

                            {{-- EXPORT --}}
                            <div class="d-flex flex-wrap">

                                <a href="{{ route('laporan.pelamar-perusahaan.excel', ['mode' => $mode] + request()->query()) }}"
                                    class="btn btn-success btn-sm mr-2 mb-2">

                                    <i class="fas fa-file-excel"></i> Excel

                                </a>

                                <a href="{{ route('laporan.pelamar-perusahaan.pdf', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-danger btn-sm mr-2 mb-2">

                                    <i class="fas fa-file-pdf"></i> PDF

                                </a>

                                <a href="{{ route('laporan.pelamar-perusahaan.print', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-secondary btn-sm mr-2 mb-2"
                                    target="_blank">

                                    <i class="fas fa-print"></i> Cetak

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

                {{-- TABLE --}}
                <div class="card-body pt-3">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover text-nowrap">

                            <thead class="bg-light">

                                <tr>

                                    <th width="5%">No</th>

                                    @if($mode == 'disnaker')
                                    <th>Nama Perusahaan</th>
                                    @endif

                                    <th>Nama Pelamar</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Pendidikan</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Jenis Pekerjaan</th>
                                    <th>Tanggal Melamar</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($data as $item)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    @if($mode == 'disnaker')
                                    <td>
                                        {{ optional(optional($item->lowongan)->profilPerusahaan)->nama_perusahaan ?? '-' }}
                                    </td>
                                    @endif

                                    <td>
                                        {{ optional($item->pencariKerja)->nama_lengkap ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($item->pencariKerja)->jenis_kelamin ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($item->pencariKerja)->pendidikan
                                        ?? optional($item->pencariKerja)->pendidikan_terakhir
                                        ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($item->lowongan)->judul_lowongan ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($item->lowongan)->jenis_pekerjaan ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($item->tanggal_lamar)->format('d-m-Y') ?? '-' }}
                                    </td>

                                </tr>

                                @empty

                                <tr>
                                    <td colspan="7" class="text-center">
                                        Data tidak ditemukan
                                    </td>
                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </section>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')

<script>
    document.getElementById('laporanSelect')
        .addEventListener('change', function() {
            window.location.href = this.value;
        });
</script>

@endpush