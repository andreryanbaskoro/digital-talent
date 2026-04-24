<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pencari Kerja</title>

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
            /* ✅ hanya garis bawah */
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            /* ❌ hilangkan kotak */
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
            <h2>Laporan Pencari Kerja</h2>
        </div>

        {{-- SUMMARY --}}
        <div class="summary">
            <div class="info-box">
                Total Data: <strong>{{ $pencariKerja->count() }}</strong>
            </div>
        </div>

        {{-- TABLE --}}
        <table class="report">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Kontak</th>
                    <th>AK1</th>
                    <th>Lamaran</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pencariKerja as $i => $item)
                <tr>

                    <td class="text-center">{{ $i + 1 }}</td>

                    <td>
                        <strong>{{ $item->nama_lengkap }}</strong><br>
                        <span class="text-muted">{{ $item->id_pencari_kerja }}</span>
                    </td>

                    <td>{{ $item->nik ?? '-' }}</td>

                    <td>
                        {{ $item->nomor_hp ?? '-' }}<br>
                        <span class="text-muted">{{ $item->email ?? '-' }}</span>
                    </td>

                    {{-- AK1 --}}
                    <td class="text-center">
                        @if($item->kartuAk1)
                        <span class="badge success">Ada</span><br>
                        <small>{{ $item->kartuAk1->nomor_pendaftaran }}</small>
                        @else
                        <span class="badge dark">Tidak</span>
                        @endif
                    </td>

                    {{-- LAMARAN --}}
                    <td class="text-center">
                        @php $total = $item->lamaranPekerjaan->count(); @endphp

                        @if($total > 0)
                        <span class="badge warning">{{ $total }}</span>
                        @else
                        -
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">
                        @if($item->deleted_at)
                        <span class="badge dark">Terhapus</span>
                        @else
                        <span class="badge success">Aktif</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted" style="padding: 14px;">
                        Tidak ada data pencari kerja
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