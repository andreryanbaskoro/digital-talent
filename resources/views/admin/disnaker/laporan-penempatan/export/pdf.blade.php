<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penempatan Tenaga Kerja</title>

    <style>
        @page {
            margin: 24px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0f172a;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-wrap {
            width: 80px;
        }

        .logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }

        .header-center {
            text-align: center;
        }

        .meta {
            width: 160px;
            text-align: right;
            font-size: 10px;
            color: #374151;
        }

        .instansi-top {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .instansi-mid {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .instansi-bottom {
            font-size: 10px;
            color: #6b7280;
        }

        /* ================= TITLE ================= */
        .title {
            text-align: center;
            margin: 10px 0 14px;
        }

        .title h2 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
        }

        /* ================= SUMMARY ================= */
        .summary {
            margin-bottom: 12px;
        }

        .info-box {
            display: inline-block;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
            font-size: 10px;
        }

        /* ================= TABLE ================= */
        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead th {
            background: #0f172a;
            color: #fff;
            padding: 7px;
            font-size: 10px;
            text-align: center;
        }

        table.report tbody td {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 10px;
            vertical-align: top;
        }

        table.report tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6b7280;
        }

        /* ================= BADGE ================= */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }

        .success {
            background: #10b981;
        }

        .warning {
            background: #f59e0b;
        }

        .danger {
            background: #ef4444;
        }

        .dark {
            background: #374151;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 20px;
        }

        .signature {
            float: right;
            text-align: center;
            width: 220px;
            margin-top: 30px;
        }

        .signature .name {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
        }

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }
    </style>
</head>

<body>

    <div class="page">

        {{-- HEADER --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    @php
                    $path = public_path('images/Lambang_Kota_Jayapura.jpeg');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp

                    <td class="logo-wrap">
                        <img src="{{ $logo }}" class="logo">
                    </td>

                    <td class="header-center">
                        <div class="instansi-top">PEMERINTAH KOTA JAYAPURA</div>
                        <div class="instansi-mid">DINAS TENAGA KERJA</div>
                        <div class="instansi-bottom">
                            Jl. Samratulangi No.25, Jayapura
                        </div>
                    </td>

                    <td class="meta">
                        Dicetak:<br>
                        {{ now()->format('d-m-Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- TITLE --}}
        <div class="title">
            <h2>Laporan Penempatan Tenaga Kerja</h2>
        </div>

        {{-- SUMMARY --}}
        <div class="summary">
            <div class="info-box">
                Total Data: <strong>{{ $penempatan->count() }}</strong>
            </div>
        </div>

        {{-- TABLE --}}
        <table class="report">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Pencari Kerja</th>
                    <th>Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tgl Lamar</th>
                    <th>Tgl Diterima</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($penempatan as $i => $item)
                <tr>

                    <td class="text-center">{{ $i + 1 }}</td>

                    {{-- PENCARI --}}
                    <td>
                        <strong>{{ $item->pencariKerja->nama_lengkap ?? '-' }}</strong><br>
                        <span class="text-muted">NIK: {{ $item->pencarikerja->nik ?? '-' }}</span>
                    </td>

                    {{-- LOWONGAN --}}
                    <td>
                        {{ $item->lowongan->judul_lowongan ?? '-' }}<br>
                        <span class="text-muted">
                            {{ $item->lowongan->lokasi ?? '-' }}
                        </span>
                    </td>

                    {{-- PERUSAHAAN --}}
                    <td>
                        {{ $item->lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}<br>
                        <span class="text-muted">
                            {{ $item->lowongan->profilPerusahaan->kab_kota ?? '-' }}
                        </span>
                    </td>

                    {{-- TANGGAL LAMAR --}}
                    <td class="text-center">
                        {{ optional($item->tanggal_lamar)->format('d M Y') ?? '-' }}
                    </td>

                    {{-- TANGGAL DITERIMA --}}
                    <td class="text-center">
                        @if(strtolower($item->status_lamaran ?? '') === 'diterima')
                        <span style="color:#10b981; font-weight:bold;">
                            {{ optional($item->updated_at)->format('d M Y') ?? '-' }}
                        </span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">

                        @if($item->deleted_at)
                        <span class="badge dark">Terhapus</span>
                        @else
                        @php
                        $status = strtolower($item->status_lamaran ?? 'diproses');
                        @endphp

                        @if($status === 'diterima')
                        <span class="badge success">Diterima</span>
                        @elseif($status === 'ditolak')
                        <span class="badge danger">Ditolak</span>
                        @elseif($status === 'diproses')
                        <span class="badge warning">Diproses</span>
                        @else
                        <span class="badge dark">{{ ucfirst($status) }}</span>
                        @endif
                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted" style="padding: 14px;">
                        Tidak ada data penempatan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- FOOTER --}}
        <div class="footer clearfix">
            <div class="signature">
                Jayapura, {{ now()->format('d F Y') }}<br>
                Kepala Dinas Tenaga Kerja<br>

                <div class="name">(........................)</div>
                NIP. ....................
            </div>
        </div>

    </div>

</body>

</html>