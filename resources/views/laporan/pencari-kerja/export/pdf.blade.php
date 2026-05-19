<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Data Pencari Kerja' }}</title>
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
            font-size: 9.5px;
            color: #1a1a2e;
            background: #fff;
            padding: 24px 28px 32px 28px;
        }

        /* =====================================================
           HEADER
        ===================================================== */
        .page-header {
            width: 100%;
            margin-bottom: 16px;
        }

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
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #5d6d7e;
            margin-bottom: 4px;
        }

        .header-inner h1 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #1a3a5c;
            margin-bottom: 3px;
        }

        .header-inner .subtitle {
            font-size: 8.5px;
            color: #7f8c8d;
            letter-spacing: 0.5px;
        }

        .divider {
            width: 100%;
            height: 1.5px;
            background: #1a5276;
            margin: 10px 0 12px 0;
        }

        /* =====================================================
           META INFO
        ===================================================== */
        .meta-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 14px;
        }

        .meta-table td {
            border: none;
            padding: 2px 0;
            font-size: 9px;
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
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 2px 8px;
            text-transform: uppercase;
        }

        .badge-success {
            background: #1e8449;
        }

        .badge-danger {
            background: #c0392b;
        }

        .badge-secondary {
            background: #7f8c8d;
        }

        /* =====================================================
           TABEL DATA
        ===================================================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table thead tr th {
            background: #1a5276;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
            padding: 6px 5px;
            letter-spacing: 0.3px;
            border: 1px solid #154360;
        }

        .data-table thead tr th.th-left {
            text-align: left;
        }

        .data-table tbody tr td {
            border: 1px solid #d5dce6;
            padding: 5px 5px;
            font-size: 8.5px;
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

        .td-tanggal {
            text-align: center;
            white-space: nowrap;
        }

        .empty-row td {
            text-align: center;
            color: #95a5a6;
            font-style: italic;
            padding: 18px 0;
            background: #f7f9fb !important;
            border: 1px solid #d5dce6;
        }

        /* Status badge inline */
        .status-badge {
            display: inline-block;
            padding: 1px 6px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* JK badge */
        .jk-l {
            color: #1a5276;
            font-weight: bold;
        }

        .jk-p {
            color: #922b21;
            font-weight: bold;
        }

        /* =====================================================
           FOOTER TANDA TANGAN
        ===================================================== */
        .footer-section {
            width: 100%;
            margin-top: 8px;
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
            font-size: 7.5px;
            color: #95a5a6;
            line-height: 1.6;
            padding-top: 6px;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-box .sig-place-date {
            font-size: 9px;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .signature-box .sig-title {
            font-size: 9px;
            font-weight: bold;
            color: #1a3a5c;
            margin-bottom: 50px;
        }

        .signature-box .sig-name {
            font-size: 9px;
            font-weight: bold;
            color: #1a1a2e;
            border-top: 1.5px solid #1a5276;
            padding-top: 4px;
            width: 160px;
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
            <h1>{{ $title ?? 'Laporan Data Pencari Kerja' }}</h1>
            <div class="subtitle">Dokumen ini digenerate secara otomatis oleh sistem</div>
        </div>
        <div class="divider"></div>
    </div>

    {{-- ======================== META INFO ======================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Mode Laporan</td>
            <td class="meta-sep">:</td>
            <td><span class="badge">{{ strtoupper($mode ?? 'disnaker') }}</span></td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Cetak</td>
            <td class="meta-sep">:</td>
            <td>{{ date('d-m-Y H:i') }} WIT</td>
        </tr>
        <tr>
            <td class="meta-label">Total Data</td>
            <td class="meta-sep">:</td>
            <td>{{ count($data) }} pencari kerja</td>
        </tr>
    </table>

    {{-- ======================== TABEL DATA ======================== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th class="th-left" style="width:13%">Nama Pencari Kerja</th>
                <th class="th-left" style="width:14%">Email</th>
                <th style="width:9%">No. Telepon</th>
                <th class="th-left" style="width:11%">Domisili</th>
                <th style="width:6%">JK</th>
                <th style="width:8%">Pendidikan</th>
                <th class="th-left" style="width:14%">Keahlian</th>
                <th class="th-left" style="width:13%">Nama Pekerjaan</th>
                <th style="width:7%">Tgl. Daftar</th>
                <th style="width:7%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            @php
            $riwayat = optional($item->kartuAk1)
            ->riwayatPendidikan
            ?->sortByDesc('tahun_lulus')
            ?->first();

            // jangan kasih '-' di sini
            $pendidikan = $riwayat?->jenjang
            ?? $item->pendidikan
            ?? $item->pendidikan_terakhir;

            $jurusan = $riwayat?->jurusan;

            $keahlian = collect(optional($item->kartuAk1)->keterampilan)
            ->pluck('nama_keterampilan')
            ->filter()
            ->implode(', ');

            $keahlian = $keahlian ?: '-';

            $lamaranTerakhir = $item->lamaranPekerjaan()
            ->withTrashed()
            ->with('lowongan')
            ->latest('tanggal_lamar')
            ->first();
            $namaPekerjaan = optional(optional($lamaranTerakhir)->lowongan)->judul_lowongan ?? '-';

            $domisili = collect([$item->kelurahan, $item->kecamatan, $item->kab_kota])
            ->filter()->implode(', ') ?: '-';

            $statusAkun = optional($item->pengguna)->status ?? '-';
            $badgeColor = match(strtolower($statusAkun)) {
            'aktif', 'active' => 'badge-success',
            'nonaktif', 'banned' => 'badge-danger',
            default => 'badge-secondary',
            };
            @endphp
            <tr>
                <td class="td-no">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->email ?? '-' }}</td>
                <td class="td-center">{{ $item->nomor_hp ?? '-' }}</td>
                <td>{{ $domisili }}</td>
                <td class="td-center">
                    @if($item->jenis_kelamin == 'L')
                    <span class="jk-l">L</span>
                    @elseif($item->jenis_kelamin == 'P')
                    <span class="jk-p">P</span>
                    @else
                    -
                    @endif
                </td>
                <td class="td-center">
                    {{
        collect([$pendidikan, $jurusan])
            ->filter()
            ->implode(' - ')
        ?: '-'
    }}
                </td>
                <td>{{ $keahlian }}</td>
                <td>{{ $namaPekerjaan }}</td>
                <td class="td-tanggal">
                    {{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}
                </td>
                <td class="td-center">
                    <span class="status-badge {{ $badgeColor }}">
                        {{ ucfirst($statusAkun) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="11">Tidak ada data pencari kerja yang ditemukan.</td>
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
                        * Data bersumber dari profil pencari kerja yang terdaftar pada sistem.
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