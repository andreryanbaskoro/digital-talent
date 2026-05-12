<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Lowongan Perusahaan</title>

    <style>
        @page {
            margin: 24px 28px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
        }

        .header {
            width: 100%;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0f172a;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sub {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #0f172a;
            color: #fff;
            padding: 8px;
            font-size: 10px;
        }

        table.report td {
            border: 1px solid #d1d5db;
            padding: 7px;
            font-size: 10px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 9px;
            color: #fff;
        }

        .pending {
            background: #f59e0b;
        }

        .disetujui {
            background: #10b981;
        }

        .ditolak {
            background: #ef4444;
        }

        .terhapus {
            background: #374151;
        }

        .footer {
            margin-top: 25px;
            float: right;
            text-align: center;
        }

        .name {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    @php
    $logoPath = public_path('images/Lambang_Kota_Jayapura.jpeg');

    $logo = null;
    if (file_exists($logoPath)) {
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_get_contents($logoPath);
    $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    @endphp

    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>

                <td style="width:80px;">
                    @if($logo)
                    <img src="{{ $logo }}" class="logo">
                    @endif
                </td>

                <td class="center">
                    @php
                    $namaPerusahaan = $lowongan->first()->profilPerusahaan->nama_perusahaan ?? 'PERUSAHAAN';
                    @endphp

                    <div class="title">
                        {{ strtoupper($namaPerusahaan) }}
                    </div>
                    <div class="title">
                        LAPORAN LOWONGAN PEKERJAAN
                    </div>

                    <div class="sub">
                        Rekap data lowongan yang dibuat oleh {{ $namaPerusahaan }}
                    </div>
                </td>

                <td style="text-align:right;">
                    <div>Laporan Resmi Perusahaan</div>
                    <div>{{ now()->format('d-m-Y H:i') }}</div>
                </td>

            </tr>
        </table>
    </div>

    {{-- INFO --}}
    <div style="margin-bottom:10px;">
        Total Data: <b>{{ $lowongan->count() }}</b>
    </div>

    {{-- TABLE --}}
    <table class="report">

        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>Perusahaan</th>
                <th>Lowongan</th>
                <th>Lokasi</th>
                <th>Jenis</th>
                <th>Kuota</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($lowongan as $i => $item)

            @php
            $status = strtolower($item->status ?? 'pending');
            @endphp

            <tr>

                <td class="text-center">{{ $i + 1 }}</td>

                <td>{{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}</td>

                <td>
                    <b>{{ $item->judul_lowongan }}</b><br>
                    <small>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 60) }}</small>
                </td>

                <td>{{ $item->lokasi ?? '-' }}</td>

                <td class="text-center">{{ ucfirst($item->jenis_pekerjaan ?? '-') }}</td>

                <td class="text-center">{{ $item->kuota ?? '-' }}</td>

                <td class="text-center">

                    @if($item->deleted_at)
                    <span class="badge terhapus">Terhapus</span>
                    @else
                    <span class="badge {{ $status }}">
                        {{ ucfirst($status) }}
                    </span>
                    @endif

                </td>

            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data lowongan</td>
            </tr>
            @endforelse

        </tbody>

    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div>Jayapura, {{ now()->format('d F Y') }}</div>
        <div>Pimpinan Perusahaan</div>
        <div class="name">(........................)</div>
    </div>

</body>

</html>