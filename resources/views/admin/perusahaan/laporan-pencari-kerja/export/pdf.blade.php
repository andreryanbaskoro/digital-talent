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

        .header {
            width: 100%;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-wrap {
            width: 80px;
        }

        .logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .header-center {
            text-align: center;
        }

        .meta {
            width: 170px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
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
            margin-top: 2px;
        }

        .instansi-bottom {
            font-size: 10px;
            margin-top: 4px;
            color: #4b5563;
        }

        .title {
            text-align: center;
            margin-bottom: 16px;
        }

        .title h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .title p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #6b7280;
        }

        .summary {
            margin-bottom: 14px;
        }

        .summary-box {
            display: inline-block;
            padding: 7px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #f9fafb;
            font-size: 10px;
            margin-right: 8px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead th {
            background: #111827;
            color: #ffffff;
            border: 1px solid #d1d5db;
            padding: 8px 6px;
            font-size: 10px;
            text-align: center;
        }

        table.report tbody td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            vertical-align: top;
            font-size: 10px;
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

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 9px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            color: #ffffff;
        }

        .badge-success {
            background: #10b981;
        }

        .badge-warning {
            background: #f59e0b;
        }

        .badge-dark {
            background: #374151;
        }

        .badge-primary {
            background: #2563eb;
        }

        .footer {
            margin-top: 28px;
        }

        .signature {
            width: 240px;
            float: right;
            text-align: center;
            font-size: 11px;
        }

        .signature-name {
            margin-top: 58px;
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

    @php
    $user = auth()->user();
    $perusahaan = $perusahaan ?? ($user->profilPerusahaan ?? null);

    $logo = null;
    $logoPath = null;

    if ($perusahaan && !empty($perusahaan->logo)) {
    $logoPath = public_path('storage/' . $perusahaan->logo);
    } else {
    $logoPath = public_path('images/Lambang_Kota_Jayapura.jpeg');
    }

    if ($logoPath && file_exists($logoPath)) {
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_get_contents($logoPath);
    $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    @endphp

    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-wrap">
                    @if($logo)
                    <img src="{{ $logo }}" class="logo">
                    @endif
                </td>

                <td class="header-center">
                    @if($perusahaan)

                    <div class="instansi-top">
                        LAPORAN PENCARI KERJA
                    </div>

                    <div class="instansi-mid">
                        {{ strtoupper($perusahaan->nama_perusahaan ?? 'PERUSAHAAN') }}
                    </div>

                    <div class="instansi-bottom">
                        {{ $perusahaan->alamat ?? 'Data pencari kerja perusahaan' }}
                    </div>

                    @else

                    <div class="instansi-top">
                        PEMERINTAH KOTA JAYAPURA
                    </div>

                    <div class="instansi-mid">
                        DINAS TENAGA KERJA
                    </div>

                    <div class="instansi-bottom">
                        Jl. Samratulangi No.25, Jayapura Utara
                    </div>

                    @endif
                </td>

                <td class="meta">
                    Dicetak:<br>
                    {{ now()->format('d-m-Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- SUMMARY --}}
    <div class="summary">
        <div class="summary-box">
            Aktif:
            <strong>{{ $pencariKerja->whereNull('deleted_at')->count() }}</strong>
        </div>

        <div class="summary-box">
            Terhapus:
            <strong>{{ $pencariKerja->whereNotNull('deleted_at')->count() }}</strong>
        </div>
    </div>

    {{-- TABLE --}}
    <table class="report">
        <thead>
            <tr>
                <th width="35">NO</th>
                <th width="180">PENCARI KERJA</th>
                <th width="110">KONTAK</th>
                <th width="110">AK1</th>
                <th width="150">LOWONGAN DILAMAR</th>
                <th width="90">STATUS</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pencariKerja as $i => $item)

            @php
            $lamaranTerakhir = $item->lamaranPekerjaan->sortByDesc('tanggal_lamar')->first();
            @endphp

            <tr>
                <td class="text-center">{{ $i + 1 }}</td>

                <td>
                    <div class="fw-bold">
                        {{ $item->nama_lengkap ?? '-' }}
                    </div>

                    <div class="small text-muted">
                        {{ $item->id_pencari_kerja }}
                    </div>

                    <div style="margin-top:4px;">
                        NIK: {{ $item->nik ?? '-' }}
                    </div>

                    <div class="small text-muted">
                        {{ $item->kab_kota ?? '-' }}
                    </div>
                </td>

                <td>
                    <div>{{ $item->nomor_hp ?? '-' }}</div>
                    <div class="small text-muted" style="margin-top:4px;">
                        {{ $item->email ?? '-' }}
                    </div>
                </td>

                <td class="text-center">
                    @if($item->kartuAk1)
                    <span class="badge badge-success">AKTIF</span>

                    <div class="small" style="margin-top:5px;">
                        {{ $item->kartuAk1->nomor_pendaftaran }}
                    </div>

                    <div class="small text-muted">
                        Berlaku:
                        {{ optional($item->kartuAk1->berlaku_sampai)->format('d M Y') ?? '-' }}
                    </div>
                    @else
                    <span class="badge badge-dark">BELUM ADA</span>
                    @endif
                </td>

                <td>
                    @if($lamaranTerakhir)
                    <div class="fw-bold">
                        {{ optional($lamaranTerakhir->lowongan)->judul_lowongan ?? '-' }}
                    </div>

                    <div class="small text-muted">
                        {{ optional($lamaranTerakhir->tanggal_lamar)->format('d M Y') ?? '-' }}
                    </div>

                    <div style="margin-top:4px;">
                        Total: {{ $item->lamaranPekerjaan->count() }} Lamaran
                    </div>
                    @else
                    <span class="text-muted">Belum Melamar</span>
                    @endif
                </td>

                <td class="text-center">
                    @if($item->deleted_at)
                    <span class="badge badge-dark">TERHAPUS</span>
                    @else
                    <span class="badge badge-primary">AKTIF</span>
                    @endif

                    <div class="small text-muted" style="margin-top:5px;">
                        {{ optional($item->created_at)->format('d M Y') }}
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6" class="text-center text-muted" style="padding:20px;">
                    Tidak ada data pencari kerja
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer clearfix">
        <div class="signature">
            Jayapura, {{ now()->translatedFormat('d F Y') }}
            <br><br>

            @if($perusahaan)
            Pimpinan Perusahaan

            <div class="signature-name">
                (...................................)
            </div>
            @else
            Kepala Dinas Tenaga Kerja

            <div class="signature-name">
                (...................................)
            </div>

            NIP. ............................
            @endif
        </div>
    </div>

</body>

</html>