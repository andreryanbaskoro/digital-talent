<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Lowongan Pekerjaan</title>
    <style>
        @page {
            margin: 24px 28px 24px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
        }

        /* KOP SURAT */
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
            /* 🔥 PENTING BIAR CENTER STABIL */
        }

        .logo-wrap {
            width: 80px;
            text-align: left;
        }

        .header-center {
            text-align: center;
            vertical-align: middle;
            width: auto;
            margin-left: 20px;
        }

        .meta {
            width: 160px;
            text-align: right;
            vertical-align: middle;
        }

        .header-table td {
            vertical-align: middle;
        }



        .logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }



        .instansi-top {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #111827;
        }

        .instansi-mid {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
            margin-top: 2px;
        }

        .instansi-bottom {
            font-size: 10px;
            color: #4b5563;
            margin-top: 3px;
        }



        /* JUDUL LAPORAN */
        .title-block {
            text-align: center;
            margin: 8px 0 14px 0;
        }

        .title-block h2 {
            margin: 0;
            font-size: 15px;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .title-block .sub {
            margin-top: 4px;
            font-size: 10px;
            color: #6b7280;
        }

        /* INFO RINGKAS */
        .summary {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .summary td {
            padding: 0;
        }

        .info-box {
            display: inline-block;
            padding: 7px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
            font-size: 10px;
            color: #374151;
        }

        /* TABLE */
        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead th {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid #0f172a;
            padding: 8px 7px;
            font-size: 10px;
            text-align: center;
            vertical-align: middle;
        }

        table.report tbody td {
            border: 1px solid #d1d5db;
            padding: 7px 7px;
            vertical-align: top;
            font-size: 10px;
            color: #111827;
        }

        table.report tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            text-transform: capitalize;
            color: #fff;
            line-height: 1;
        }

        .badge-pending {
            background: #f59e0b;
        }

        .badge-disetujui {
            background: #10b981;
        }

        .badge-ditolak {
            background: #ef4444;
        }

        .badge-terhapus {
            background: #374151;
        }

        .footer {
            margin-top: 18px;
            font-size: 10px;
            color: #374151;
        }

        .signature {
            width: 220px;
            float: right;
            text-align: center;
            margin-top: 24px;
        }

        .signature .city {
            margin-bottom: 42px;
        }

        .signature .name {
            margin-top: 52px;
            font-weight: bold;
            text-decoration: underline;
        }

        .signature .nip {
            margin-top: 4px;
            font-size: 9px;
            color: #6b7280;
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

        {{-- KOP SURAT --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    @php
                    $path = public_path('images/Lambang_Kota_Jayapura.jpeg');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp

                    {{-- LEFT LOGO --}}
                    <td class="logo-wrap">
                        <img src="{{ $base64 }}" class="logo">
                    </td>

                    {{-- CENTER --}}
                    <td class="header-center">
                        <div class="instansi-top">PEMERINTAH KOTA JAYAPURA</div>
                        <div class="instansi-mid">DINAS TENAGA KERJA</div>
                        <div class="instansi-bottom">
                            Jl. Samratulangi No.25, Mandala, Jayapura Utara
                        </div>
                    </td>

                    {{-- RIGHT META --}}
                    <td class="meta">
                        <div>Laporan Resmi</div>
                        <div>Dicetak: {{ now()->format('d-m-Y H:i') }}</div>
                    </td>

                </tr>
            </table>
        </div>

        {{-- JUDUL --}}
        <div class="title-block">
            <h2>Laporan Lowongan Pekerjaan</h2>
            <div class="sub">Rekap data lowongan pekerjaan yang tersedia, termasuk data terhapus</div>
        </div>

        {{-- INFO --}}
        <table class="summary">
            <tr>
                <td>
                    <div class="info-box">
                        Total Data: <strong>{{ $lowongan->count() }}</strong>
                    </div>
                </td>
                <td class="text-right">
                    <div class="info-box">
                        Periode Cetak: <strong>{{ now()->format('d F Y') }}</strong>
                    </div>
                </td>
            </tr>
        </table>

        {{-- TABEL --}}
        <table class="report">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 150px;">Perusahaan</th>
                    <th>Judul Lowongan</th>
                    <th style="width: 90px;">Lokasi</th>
                    <th style="width: 85px;">Jenis</th>
                    <th style="width: 80px;">Kuota</th>
                    <th style="width: 110px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowongan as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}</td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 2px;">
                            {{ $item->judul_lowongan }}
                        </div>
                        <div class="text-muted">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 80) }}
                        </div>
                    </td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="text-center">
                        {{ ucfirst($item->jenis_pekerjaan ?? '-') }}
                    </td>
                    <td class="text-center">
                        {{ $item->kuota ?? '-' }}
                    </td>
                    <td class="text-center">
                        @if($item->deleted_at)
                        <span class="badge badge-terhapus">Terhapus</span>
                        @else
                        @php
                        $status = strtolower($item->status ?? 'pending');
                        @endphp

                        @if($status === 'pending')
                        <span class="badge badge-pending">Pending</span>
                        @elseif($status === 'disetujui')
                        <span class="badge badge-disetujui">Disetujui</span>
                        @elseif($status === 'ditolak')
                        <span class="badge badge-ditolak">Ditolak</span>
                        @else
                        <span class="badge badge-terhapus">{{ ucfirst($item->status ?? '-') }}</span>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted" style="padding: 16px;">
                        Tidak ada data lowongan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- TANDA TANGAN --}}
        <div class="footer clearfix">
            <div class="signature">
                <div class="city">Jayapura, {{ now()->format('d F Y') }}</div>
                <div>Kepala Dinas Tenaga Kerja</div>
                <div class="name">(..........................)</div>
                <div class="nip">NIP. ..........................</div>
            </div>
        </div>

    </div>
</body>


</html>