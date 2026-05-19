<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Rekapitulasi Profile Matching' }}</title>
    <style>
        /* =====================================================
           BASE
        ===================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1a1a2e;
            background: #fff;
            padding: 20px 24px 28px 24px;
        }

        /* =====================================================
           HEADER
        ===================================================== */
        .page-header {
            width: 100%;
            margin-bottom: 14px;
        }

        .accent-bar {
            width: 100%;
            height: 4px;
            background: #1a5276;
            margin-bottom: 10px;
        }

        .header-inner {
            text-align: center;
        }

        .header-inner .label-instansi {
            font-size: 8.5px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #5d6d7e;
            margin-bottom: 3px;
        }

        .header-inner h1 {
            font-size: 12.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a3a5c;
            margin-bottom: 3px;
        }

        .header-inner .subtitle {
            font-size: 8px;
            color: #7f8c8d;
        }

        .divider {
            width: 100%;
            height: 1.5px;
            background: #1a5276;
            margin: 8px 0 10px 0;
        }

        /* =====================================================
           META INFO
        ===================================================== */
        .meta-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        .meta-table td {
            border: none;
            padding: 2px 0;
            font-size: 8.5px;
            color: #2c3e50;
            vertical-align: top;
        }

        .meta-table td.meta-label {
            width: 110px;
            font-weight: bold;
            color: #1a3a5c;
        }

        .meta-table td.meta-sep {
            width: 12px;
            text-align: center;
        }

        .badge-mode {
            display: inline-block;
            background: #1a5276;
            color: #fff;
            font-size: 7.5px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 2px 7px;
            text-transform: uppercase;
        }

        /* =====================================================
           STATISTIK KATEGORI
        ===================================================== */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .stats-table td {
            border: none;
            padding: 0 6px 0 0;
            width: 25%;
            vertical-align: top;
        }

        .stat-box {
            border-radius: 3px;
            padding: 6px 10px;
            text-align: center;
        }

        .stat-box .stat-num {
            font-size: 16px;
            font-weight: bold;
            line-height: 1;
        }

        .stat-box .stat-label {
            font-size: 7.5px;
            margin-top: 2px;
        }

        .stat-total {
            background: #d6eaf8;
            color: #1a5276;
        }

        .stat-sangat {
            background: #d5f5e3;
            color: #1e8449;
        }

        .stat-cocok {
            background: #d6eaf8;
            color: #1a5276;
        }

        .stat-kurang {
            background: #fadbd8;
            color: #c0392b;
        }

        /* =====================================================
           TABEL DATA
        ===================================================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .data-table thead tr th {
            background: #1a5276;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            padding: 5px 4px;
            letter-spacing: 0.2px;
            border: 1px solid #154360;
        }

        .data-table thead tr th.th-left {
            text-align: left;
        }

        .data-table tbody tr td {
            border: 1px solid #d5dce6;
            padding: 4px 4px;
            font-size: 8px;
            color: #1a1a2e;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) td {
            background: #eaf2fb;
        }

        .data-table tbody tr:nth-child(odd) td {
            background: #ffffff;
        }

        .td-center {
            text-align: center;
        }

        .td-no {
            text-align: center;
            font-weight: bold;
            color: #1a5276;
        }

        .td-date {
            text-align: center;
            white-space: nowrap;
        }

        /* Status / badge inline */
        .kes-badge {
            display: inline-block;
            padding: 1px 5px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .kes-sangat {
            background: #d5f5e3;
            color: #1e8449;
        }

        .kes-cocok {
            background: #d6eaf8;
            color: #1a5276;
        }

        .kes-kurang {
            background: #fadbd8;
            color: #c0392b;
        }

        .kes-default {
            background: #f2f3f4;
            color: #7f8c8d;
        }

        .jk-l {
            color: #1a5276;
            font-weight: bold;
        }

        .jk-p {
            color: #922b21;
            font-weight: bold;
        }

        .rank-badge {
            display: inline-block;
            background: #2c3e50;
            color: #fff;
            font-size: 7px;
            font-weight: bold;
            padding: 1px 5px;
        }

        .persen-sangat {
            color: #1e8449;
            font-weight: bold;
        }

        .persen-cocok {
            color: #1a5276;
            font-weight: bold;
        }

        .persen-kurang {
            color: #c0392b;
        }

        .empty-row td {
            text-align: center;
            color: #95a5a6;
            font-style: italic;
            padding: 16px 0;
            background: #f7f9fb !important;
            border: 1px solid #d5dce6;
        }

        /* =====================================================
           FOOTER TANDA TANGAN
        ===================================================== */
        .footer-section {
            width: 100%;
            margin-top: 6px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            vertical-align: top;
            padding: 0;
        }

        .footer-left {
            width: 60%;
        }

        .footer-note {
            font-size: 7px;
            color: #95a5a6;
            line-height: 1.6;
            padding-top: 4px;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-box .sig-place-date {
            font-size: 8.5px;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .signature-box .sig-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #1a3a5c;
            margin-bottom: 44px;
        }

        .signature-box .sig-name {
            font-size: 8.5px;
            font-weight: bold;
            color: #1a1a2e;
            border-top: 1.5px solid #1a5276;
            padding-top: 3px;
            width: 150px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    {{-- ======================== HEADER ======================== --}}
    <div class="page-header">
        <div class="accent-bar"></div>
        <div class="header-inner">
            <div class="label-instansi">
                {{ ($mode ?? 'disnaker') === 'disnaker' ? 'Dinas Tenaga Kerja' : 'Laporan Internal Perusahaan' }}
            </div>
            <h1>{{ $title ?? 'Laporan Rekapitulasi Profile Matching' }}</h1>
            <div class="subtitle">Dokumen ini digenerate secara otomatis oleh sistem informasi ketenagakerjaan</div>
        </div>
        <div class="divider"></div>
    </div>

    {{-- ======================== META INFO ======================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Mode Laporan</td>
            <td class="meta-sep">:</td>
            <td><span class="badge-mode">{{ strtoupper($mode ?? 'disnaker') }}</span></td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Cetak</td>
            <td class="meta-sep">:</td>
            <td>{{ date('d-m-Y H:i') }} WIT</td>
        </tr>
        <tr>
            <td class="meta-label">Total Data</td>
            <td class="meta-sep">:</td>
            <td>{{ count($data) }} hasil seleksi</td>
        </tr>
    </table>

    {{-- ======================== STATISTIK KATEGORI ======================== --}}
    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-box stat-total">
                    <div class="stat-num">{{ count($data) }}</div>
                    <div class="stat-label">Total Seleksi</div>
                </div>
            </td>
            <td>
                <div class="stat-box stat-sangat">
                    <div class="stat-num">{{ $totalSangatCocok ?? 0 }}</div>
                    <div class="stat-label">⭐ Sangat Cocok</div>
                </div>
            </td>
            <td>
                <div class="stat-box stat-cocok">
                    <div class="stat-num">{{ $totalCocok ?? 0 }}</div>
                    <div class="stat-label">👍 Cocok</div>
                </div>
            </td>
            <td style="padding-right:0">
                <div class="stat-box stat-kurang">
                    <div class="stat-num">{{ $totalKurangCocok ?? 0 }}</div>
                    <div class="stat-label">❗ Kurang Cocok</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ======================== TABEL DATA ======================== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th class="th-left" style="width:{{ $mode === 'disnaker' ? '13%' : '18%' }}">Nama Pelamar</th>
                @if(($mode ?? 'disnaker') === 'disnaker')
                <th style="width:5%">JK</th>
                <th class="th-left" style="width:12%">Perusahaan</th>
                @endif
                <th class="th-left" style="width:{{ $mode === 'disnaker' ? '14%' : '20%' }}">Lowongan</th>
                <th style="width:8%">Core Factor</th>
                <th style="width:8%">Sec. Factor</th>
                <th style="width:9%">Persentase</th>
                <th style="width:5%">Rank</th>
                <th style="width:{{ $mode === 'disnaker' ? '11%' : '13%' }}">Kesimpulan</th>
                <th style="width:8%">Tgl. Seleksi</th>
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
            $secondaryFactor = (float) ($item->nilai_faktor_pendukung ?? 0);
            $ranking = $item->peringkat ?? '-';
            $rekomendasi = $item->rekomendasi ?? '-';
            $tanggalSeleksi = $item->created_at ? $item->created_at->format('d-m-Y') : '-';

            // Class kesimpulan — exact match sesuai getRekomendasi()
            $kesClass = match($rekomendasi) {
            '⭐ Sangat Cocok' => 'kes-sangat',
            '👍 Cocok' => 'kes-cocok',
            '❗ Kurang Cocok' => 'kes-kurang',
            default => 'kes-default',
            };

            // Class persentase
            $persenClass = 'persen-kurang';
            if ($persentase >= 85) $persenClass = 'persen-sangat';
            elseif ($persentase >= 70) $persenClass = 'persen-cocok';
            @endphp
            <tr>
                <td class="td-no">{{ $loop->iteration }}</td>
                <td>{{ $namaLengkap }}</td>
                @if(($mode ?? 'disnaker') === 'disnaker')
                <td class="td-center">
                    @if($jenisKelamin == 'L')
                    <span class="jk-l">L</span>
                    @elseif($jenisKelamin == 'P')
                    <span class="jk-p">P</span>
                    @else
                    -
                    @endif
                </td>
                <td>{{ $namaPerusahaan }}</td>
                @endif
                <td>{{ $judulLowongan }}</td>
                <td class="td-center">{{ number_format($coreFactor, 2) }}</td>
                <td class="td-center">{{ number_format($secondaryFactor, 2) }}</td>
                <td class="td-center">
                    <span class="{{ $persenClass }}">{{ $persentase }}%</span>
                </td>
                <td class="td-center">
                    <span class="rank-badge">#{{ $ranking }}</span>
                </td>
                <td class="td-center">
                    <span class="kes-badge {{ $kesClass }}">{{ $rekomendasi }}</span>
                </td>
                <td class="td-date">{{ $tanggalSeleksi }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="{{ ($mode ?? 'disnaker') === 'disnaker' ? 11 : 9 }}">
                    Tidak ada data hasil profile matching yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ======================== FOOTER TANDA TANGAN ======================== --}}
    <div class="footer-section">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <div class="footer-note">
                        * Laporan ini dicetak secara otomatis dari sistem informasi ketenagakerjaan.<br>
                        * Data bersumber dari hasil perhitungan Profile Matching yang tersimpan pada sistem.<br>
                        * Persentase dihitung dari nilai total (skala 0–5) × 20.
                    </div>
                </td>
                <td style="text-align:right">
                    <div class="signature-box">
                        <div class="sig-place-date">Jayapura, {{ date('d-m-Y') }}</div>
                        <div class="sig-title">
                            {{ ($mode ?? 'disnaker') === 'disnaker' ? 'Kepala Dinas Tenaga Kerja' : 'Pimpinan Perusahaan' }}
                        </div>
                        <div class="sig-name">(.................................)</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</html>