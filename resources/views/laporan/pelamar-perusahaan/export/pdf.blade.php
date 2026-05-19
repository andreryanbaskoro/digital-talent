<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Data Pelamar Perusahaan' }}</title>
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
            font-size: 10px;
            color: #1a1a2e;
            background: #fff;
            padding: 28px 32px 36px 32px;
        }

        /* =====================================================
           HEADER
        ===================================================== */
        .page-header {
            width: 100%;
            margin-bottom: 18px;
        }

        /* Accent bar di atas header */
        .accent-bar {
            width: 100%;
            height: 4px;
            background: #1a5276;
            margin-bottom: 12px;
        }

        .header-inner {
            text-align: center;
        }

        .header-inner .label-instansi {
            font-size: 9.5px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #5d6d7e;
            margin-bottom: 4px;
        }

        .header-inner h1 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #1a3a5c;
            margin-bottom: 3px;
        }

        .header-inner .subtitle {
            font-size: 9px;
            color: #7f8c8d;
            letter-spacing: 0.5px;
        }

        .divider {
            width: 100%;
            height: 1.5px;
            background: #1a5276;
            margin: 10px 0 14px 0;
        }

        /* =====================================================
           META INFO
        ===================================================== */
        .meta-row {
            width: 100%;
            margin-bottom: 14px;
        }

        .meta-table {
            border-collapse: collapse;
            width: 100%;
        }

        .meta-table td {
            border: none;
            padding: 2px 0;
            font-size: 9.5px;
            color: #2c3e50;
            vertical-align: top;
        }

        .meta-table td.meta-label {
            width: 100px;
            font-weight: bold;
            color: #1a3a5c;
        }

        .meta-table td.meta-sep {
            width: 12px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            background: #1a5276;
            color: #fff;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 2px 8px;
            text-transform: uppercase;
        }

        /* =====================================================
           TABEL DATA
        ===================================================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .data-table thead tr th {
            background: #1a5276;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            padding: 7px 6px;
            letter-spacing: 0.4px;
            border: 1px solid #154360;
        }

        .data-table thead tr th.th-left {
            text-align: left;
        }

        .data-table tbody tr td {
            border: 1px solid #d5dce6;
            padding: 6px 6px;
            font-size: 9.5px;
            color: #1a1a2e;
            vertical-align: middle;
        }

        /* Zebra stripe */
        .data-table tbody tr:nth-child(even) td {
            background: #eaf2fb;
        }

        .data-table tbody tr:nth-child(odd) td {
            background: #ffffff;
        }

        /* Hover tidak berlaku di PDF tapi tetap ditulis untuk konsistensi */
        .td-center {
            text-align: center;
        }

        .td-no {
            text-align: center;
            width: 5%;
            font-weight: bold;
            color: #1a5276;
        }

        .td-tanggal {
            text-align: center;
            white-space: nowrap;
        }

        /* Baris kosong / empty state */
        .empty-row td {
            text-align: center;
            color: #95a5a6;
            font-style: italic;
            padding: 18px 0;
            background: #f7f9fb !important;
            border: 1px solid #d5dce6;
        }

        /* =====================================================
           FOOTER TANDA TANGAN
        ===================================================== */
        .footer-section {
            width: 100%;
            margin-top: 10px;
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

        /* Catatan kaki kecil di kiri bawah */
        .footer-note {
            font-size: 8px;
            color: #95a5a6;
            line-height: 1.6;
            padding-top: 6px;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-box .sig-place-date {
            font-size: 9.5px;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .signature-box .sig-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #1a3a5c;
            margin-bottom: 52px;
            /* ruang tanda tangan */
        }

        .signature-box .sig-name {
            font-size: 9.5px;
            font-weight: bold;
            color: #1a1a2e;
            border-top: 1.5px solid #1a5276;
            padding-top: 4px;
            width: 160px;
            margin: 0 auto;
        }

        /* =====================================================
           PAGE NUMBER (jika multi-page)
        ===================================================== */
        .page-number {
            font-size: 8px;
            color: #aab7c4;
            text-align: center;
            margin-top: 14px;
            letter-spacing: 0.5px;
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
            <h1>{{ $title ?? 'Laporan Data Pelamar Perusahaan' }}</h1>
            <div class="subtitle">Dokumen ini digenerate secara otomatis oleh sistem</div>
        </div>
        <div class="divider"></div>
    </div>

    {{-- ======================== META INFO ======================== --}}
    <div class="meta-row">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Mode Laporan</td>
                <td class="meta-sep">:</td>
                <td>
                    <span class="badge">{{ strtoupper($mode ?? 'disnaker') }}</span>
                </td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Cetak</td>
                <td class="meta-sep">:</td>
                <td>{{ date('d-m-Y H:i') }} WIT</td>
            </tr>
            <tr>
                <td class="meta-label">Total Data</td>
                <td class="meta-sep">:</td>
                <td>{{ count($data) }} pelamar</td>
            </tr>
        </table>
    </div>

    {{-- ======================== TABEL DATA ======================== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:4%">No</th>

                @if(($mode ?? 'disnaker') === 'disnaker')
                <th class="th-left" style="width:15%">Nama Perusahaan</th>
                @endif

                <th class="th-left" style="width:{{ ($mode ?? 'disnaker') === 'disnaker' ? '15%' : '18%' }}">Nama Pelamar</th>
                <th style="width:8%">Jenis Kelamin</th>
                <th style="width:10%">Pendidikan</th>
                <th class="th-left" style="width:{{ ($mode ?? 'disnaker') === 'disnaker' ? '18%' : '22%' }}">Nama Pekerjaan</th>
                <th style="width:12%">Jenis Pekerjaan</th>
                <th style="width:10%">Tgl. Melamar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td class="td-no">{{ $loop->iteration }}</td>

                @if(($mode ?? 'disnaker') === 'disnaker')
                <td>{{ optional(optional($item->lowongan)->profilPerusahaan)->nama_perusahaan ?? '-' }}</td>
                @endif

                <td>{{ optional($item->pencariKerja)->nama_lengkap ?? '-' }}</td>
                <td class="td-center">{{ optional($item->pencariKerja)->jenis_kelamin ?? '-' }}</td>
                <td class="td-center">
                    {{ optional($item->pencariKerja)->pendidikan
                        ?? optional($item->pencariKerja)->pendidikan_terakhir
                        ?? '-' }}
                </td>
                <td>{{ optional($item->lowongan)->judul_lowongan ?? '-' }}</td>
                <td class="td-center">{{ optional($item->lowongan)->jenis_pekerjaan ?? '-' }}</td>
                <td class="td-tanggal">
                    {{ $item->tanggal_lamar ? $item->tanggal_lamar->format('d-m-Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="{{ ($mode ?? 'disnaker') === 'disnaker' ? 8 : 7 }}">
                    Tidak ada data pelamar yang ditemukan.
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
                        * Data bersumber dari pengajuan lamaran yang masuk pada sistem.
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