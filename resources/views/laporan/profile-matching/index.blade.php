@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0">
                    {{ $title ?? 'Laporan Rekapitulasi Profile Matching' }}
                </h1>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            @php
            $total = $data->count();
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
                                    <option value="{{ route('laporan.pencari-kerja.index', ['mode' => $mode]) }}">
                                        🔍 Laporan Data Pencari Kerja
                                    </option>
                                    <option value="{{ route('laporan.pelamar-perusahaan.index', ['mode' => $mode]) }}">
                                        🏢 Laporan Data Pelamar Perusahaan
                                    </option>
                                    <option
                                        value="{{ route('laporan.profile-matching.index', ['mode' => $mode]) }}"
                                        selected>
                                        📊 Laporan Rekapitulasi Profile Matching
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD STATISTIK --}}
                <div class="col-md-9">
                    <div class="row row-cols-1 row-cols-md-4">
                        <div class="col mb-4">
                            <div class="small-box bg-info h-100">
                                <div class="inner">
                                    <h3>{{ $total }}</h3>
                                    <p>Total Hasil Seleksi</p>
                                </div>
                                <div class="icon"><i class="fas fa-chart-bar"></i></div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="small-box bg-success h-100">
                                <div class="inner">
                                    <h3>{{ $totalSangatCocok }}</h3>
                                    <p>⭐ Sangat Cocok</p>
                                </div>
                                <div class="icon"><i class="fas fa-star"></i></div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="small-box bg-primary h-100">
                                <div class="inner">
                                    <h3>{{ $totalCocok }}</h3>
                                    <p>👍 Cocok</p>
                                </div>
                                <div class="icon"><i class="fas fa-thumbs-up"></i></div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="small-box bg-danger h-100">
                                <div class="inner">
                                    <h3>{{ $totalKurangCocok }}</h3>
                                    <p>❗ Kurang Cocok</p>
                                </div>
                                <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- CARD TABLE --}}
            <div class="card card-primary card-outline shadow-sm">

                {{-- FILTER + EXPORT --}}
                <div class="card-header border-0 pb-0">

                    <form method="GET"
                        action="{{ route('laporan.profile-matching.index', ['mode' => $mode]) }}">

                        <div class="row">

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

                            @if($mode === 'disnaker')
                            {{-- JENIS PEKERJAAN (hanya disnaker) --}}
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
                            @endif

                            <div class="col-md-3 mb-3">
                                <label>Periode Lowongan</label>
                                <select name="periode_ke" class="form-control">
                                    <option value="">-- Semua Periode --</option>
                                    @foreach($periodeOptions as $periode)
                                    <option value="{{ $periode->periode_ke }}"
                                        {{ request('periode_ke') == $periode->periode_ke ? 'selected' : '' }}>
                                        Periode {{ $periode->periode_ke }}
                                        {{ $periode->nama_periode ? '- ' . $periode->nama_periode : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- KESIMPULAN --}}
                            <div class="col-md-3 mb-3">
                                <label>Kesimpulan Hasil</label>
                                <select name="kesimpulan" class="form-control">
                                    <option value="">-- Semua Kesimpulan --</option>
                                    @foreach($kesimpulanOptions as $kes)
                                    <option
                                        value="{{ $kes }}"
                                        {{ request('kesimpulan') == $kes ? 'selected' : '' }}>
                                        {{ $kes }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- BUTTON FILTER + EXPORT --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center">

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('laporan.profile-matching.index', ['mode' => $mode]) }}"
                                    class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>

                            {{-- EXPORT --}}
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('laporan.profile-matching.excel', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-success btn-sm mr-2 mb-2">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="{{ route('laporan.profile-matching.pdf', array_merge(['mode' => $mode], request()->query())) }}"
                                    class="btn btn-danger btn-sm mr-2 mb-2">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('laporan.profile-matching.print', array_merge(['mode' => $mode], request()->query())) }}"
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
                                    <th>Nama Pelamar</th>
                                    @if($mode === 'disnaker')
                                    <th>Jenis Kelamin</th>
                                    <th>Perusahaan</th>
                                    @endif
                                    <th>Lowongan</th>
                                    <th class="text-center">Core Factor</th>
                                    <th class="text-center">Secondary Factor</th>
                                    <th class="text-center">Persentase Matching</th>
                                    <th class="text-center">Ranking</th>
                                    <th class="text-center">Kesimpulan</th>
                                    <th class="text-center">Periode</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($data as $item)
                                @php
                                $pencariKerja = optional($item->lamaran->pencariKerja);
                                $lowongan = optional($item->lamaran->lowongan);
                                $perusahaan = optional($lowongan->profilPerusahaan);

                                $namaLengkap = $pencariKerja->nama_lengkap ?? '-';
                                $jenisKelamin = $pencariKerja->jenis_kelamin ?? null;
                                $namaPerusahaan = $perusahaan->nama_perusahaan ?? '-';
                                $judulLowongan = $lowongan->judul_lowongan ?? '-';

                                $nilaiTotal = (float) ($item->nilai_total ?? 0);
                                $persentase = round($nilaiTotal * 20, 2);
                                $coreFactor = (float) ($item->nilai_faktor_inti ?? 0);
                                $secondaryFactor= (float) ($item->nilai_faktor_pendukung ?? 0);
                                $ranking = $item->peringkat ?? '-';
                                $rekomendasi = $item->rekomendasi ?? '-';
                                $lamaran = optional($item->lamaran);

                                $periode = $lamaran->periode_ke
                                ? 'Periode ' . $lamaran->periode_ke
                                : '-';

                                if ($lamaran->nama_periode) {
                                $periode .= ' - ' . $lamaran->nama_periode;
                                }

                                // Badge warna kesimpulan — pakai exact match sesuai getRekomendasi()
                                $badgeClass = match($rekomendasi) {
                                '⭐ Sangat Cocok' => 'success',
                                '👍 Cocok' => 'primary',
                                '❗ Kurang Cocok' => 'danger',
                                default => 'secondary',
                                };

                                // Warna persentase
                                $persenClass = 'text-danger';
                                if ($persentase >= 85) $persenClass = 'text-success font-weight-bold';
                                elseif ($persentase >= 70) $persenClass = 'text-primary font-weight-bold';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $namaLengkap }}</td>
                                    @if($mode === 'disnaker')
                                    <td class="text-center">
                                        @if($jenisKelamin == 'L')
                                        <span class="badge badge-primary">Laki-laki</span>
                                        @elseif($jenisKelamin == 'P')
                                        <span class="badge badge-danger">Perempuan</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $namaPerusahaan }}</td>
                                    @endif
                                    <td>{{ $judulLowongan }}</td>
                                    <td class="text-center">{{ number_format($coreFactor, 2) }}</td>
                                    <td class="text-center">{{ number_format($secondaryFactor, 2) }}</td>
                                    <td class="text-center">
                                        <span class="{{ $persenClass }}">{{ $persentase }}%</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-dark">#{{ $ranking }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $badgeClass }}">
                                            {{ $rekomendasi }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">
                                            {{ $periode }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $mode === 'disnaker' ? 11 : 9 }}" class="text-center">
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
<style>
    .small-box .inner h3 {
        font-size: 2rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('laporanSelect')
        .addEventListener('change', function() {
            window.location.href = this.value;
        });
</script>
@endpush