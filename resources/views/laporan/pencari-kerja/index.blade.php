@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0">
                    {{ $title ?? 'Laporan Data Pencari Kerja' }}
                </h1>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            @php
            $total = $data->count();
            $laki = $data->filter(fn ($item) => optional($item->pencariKerja)->jenis_kelamin === 'L')->count();
            $perempuan = $data->filter(fn ($item) => optional($item->pencariKerja)->jenis_kelamin === 'P')->count();

            $cards = [
            ['title' => 'Total Pencari Kerja', 'count' => $total, 'color' => 'info', 'icon' => 'users'],
            ['title' => 'Laki-laki', 'count' => $laki, 'color' => 'primary', 'icon' => 'male'],
            ['title' => 'Perempuan', 'count' => $perempuan, 'color' => 'danger', 'icon' => 'female'],
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
                                    <option value="{{ route('laporan.pencari-kerja.index', ['mode' => $mode]) }}" selected>
                                        🔍 Laporan Data Pencari Kerja
                                    </option>
                                    <option value="{{ route('laporan.pelamar-perusahaan.index', ['mode' => $mode]) }}">
                                        🏢 Laporan Data Pelamar Perusahaan
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

                    <form method="GET"
                        action="{{ route('laporan.pencari-kerja.index', ['mode' => $mode]) }}">

                        <div class="row">

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

                            {{-- BULAN PENDAFTARAN --}}
                            <div class="col-md-3 mb-3">
                                <label>Bulan/Tahun</label>
                                <input type="month"
                                    name="tanggal_pendaftaran"
                                    class="form-control"
                                    value="{{ request('tanggal_pendaftaran') }}">
                            </div>

                            {{-- JENIS KELAMIN --}}
                            <div class="col-md-3 mb-3">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">-- Semua --</option>
                                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                        </div>

                        {{-- BUTTON FILTER + EXPORT --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center">

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('laporan.pencari-kerja.index', ['mode' => $mode]) }}"
                                    class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>

                            {{-- EXPORT --}}
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('laporan.pencari-kerja.excel', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-success btn-sm mr-2 mb-2">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="{{ route('laporan.pencari-kerja.pdf', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-danger btn-sm mr-2 mb-2">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('laporan.pencari-kerja.print', array_merge(['mode' => $mode], request()->query())) }}"
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
                                    <th width="4%">No</th>
                                    <th>Nama Pencari Kerja</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Domisili</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Pendidikan Terakhir</th>
                                    <th>Keahlian</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Tanggal Mendaftar</th>
                                    <th>Status Akun</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($data as $item)
                                @php
                                $pencariKerja = optional($item->pencariKerja);
                                $lowongan = optional($item->lowongan);

                                // Riwayat pendidikan terakhir
                                $riwayat = optional($pencariKerja->kartuAk1)
                                ->riwayatPendidikan
                                ?->sortByDesc('tahun_lulus')
                                ?->first();

                                // Pendidikan
                                $pendidikan = $riwayat?->jenjang
                                ?? $pencariKerja->pendidikan
                                ?? $pencariKerja->pendidikan_terakhir;

                                // Jurusan
                                $jurusan = $riwayat?->jurusan;

                                // Keahlian
                                $keahlian = optional($pencariKerja->kartuAk1)
                                ->keterampilan
                                ?->pluck('nama_keterampilan')
                                ?->filter()
                                ?->implode(', ');
                                $keahlian = $keahlian ?: '-';

                                // Pekerjaan yang dilamar
                                $namaPekerjaan = $lowongan->judul_lowongan ?? '-';

                                // Domisili
                                $domisili = collect([
                                $pencariKerja->kelurahan,
                                $pencariKerja->kecamatan,
                                $pencariKerja->kab_kota,
                                ])->filter()->implode(', ') ?: '-';

                                // Status akun
                                $statusAkun = optional($pencariKerja->pengguna)->status ?? '-';

                                // Badge status
                                $badgeColor = match(strtolower($statusAkun)) {
                                'aktif', 'active' => 'success',
                                'nonaktif', 'banned' => 'danger',
                                default => 'secondary',
                                };
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    {{-- Nama --}}
                                    <td>
                                        {{ $pencariKerja->nama_lengkap ?? '-' }}
                                    </td>

                                    {{-- Email --}}
                                    <td>
                                        {{ $pencariKerja->email ?? '-' }}
                                    </td>

                                    {{-- Nomor HP --}}
                                    <td>
                                        {{ $pencariKerja->nomor_hp ?? '-' }}
                                    </td>

                                    {{-- Domisili --}}
                                    <td>
                                        {{ $domisili }}
                                    </td>

                                    {{-- Jenis Kelamin --}}
                                    <td class="text-center">
                                        @if($pencariKerja->jenis_kelamin == 'L')
                                        <span class="badge badge-primary">
                                            Laki-laki
                                        </span>

                                        @elseif($pencariKerja->jenis_kelamin == 'P')
                                        <span class="badge badge-danger">
                                            Perempuan
                                        </span>

                                        @else
                                        -
                                        @endif
                                    </td>

                                    {{-- Pendidikan --}}
                                    <td class="text-center">
                                        {{
            collect([$pendidikan, $jurusan])
                ->filter()
                ->implode(' - ')
                ?: '-'
        }}
                                    </td>

                                    {{-- Keahlian --}}
                                    <td>
                                        <span title="{{ $keahlian }}">
                                            {{ Str::limit($keahlian, 40) }}
                                        </span>
                                    </td>

                                    {{-- Nama Pekerjaan --}}
                                    <td>
                                        {{ $namaPekerjaan }}
                                    </td>

                                    {{-- Tanggal Lamar --}}
                                    <td class="text-center">
                                        {{
            $item->tanggal_lamar
                ? \Carbon\Carbon::parse($item->tanggal_lamar)->format('d-m-Y')
                : '-'
        }}
                                    </td>

                                    {{-- Status Akun --}}
                                    <td class="text-center">
                                        <span class="badge badge-{{ $badgeColor }}">
                                            {{ ucfirst($statusAkun) }}
                                        </span>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        Data tidak ditemukan
                                    </td>
                                </tr>
                                <td colspan="11" class="text-center">Data tidak ditemukan</td>
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