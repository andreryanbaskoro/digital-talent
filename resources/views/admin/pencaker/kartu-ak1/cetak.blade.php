<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cetak AK1' }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
            background: #f5f5f5;
        }

        .no-print {
            max-width: 1120px;
            margin: 12px auto;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid #333;
            text-decoration: none;
            color: #111;
            background: #fff;
            font-size: 12px;
            border-radius: 4px;
        }

        .btn-primary {
            background: #1f6feb;
            color: #fff;
            border-color: #1f6feb;
        }

        .page {
            max-width: 1120px;
            margin: 0 auto 20px;
            background: #fff;
            padding: 0;
        }

        .panel {
            border: 1px solid #444;
            margin-bottom: 22px;
            width: 100%;
        }

        .panel td {
            vertical-align: top;
        }

        .left-col {
            width: 46%;
            padding: 8px;
            border-right: 1px solid #444;
        }

        .right-col {
            width: 54%;
            padding: 8px;
        }

        .section-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .small-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .muted {
            color: #555;
        }

        .line {
            height: 1px;
            background: #444;
            margin: 4px 0 6px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 2px;
            line-height: 1.35;
        }

        .item-row .left {
            flex: 1;
        }

        .item-row .right {
            white-space: nowrap;
        }

        .bullet {
            margin: 0 0 2px 14px;
            padding: 0;
            line-height: 1.35;
        }

        .header-box {
            text-align: center;
            margin-bottom: 4px;
            line-height: 1.25;
        }

        .header-flex {
            display: flex;
            justify-content: center;
            /* posisi grup di tengah */
            align-items: center;
            gap: 8px;
            /* jarak logo & teks (diperkecil) */
            margin-bottom: 6px;
            text-align: center;
        }

        .header-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            line-height: 1.3;
            /* ❌ HAPUS flex:1 biar tidak melebar */
        }

        .header-text .title,
        .header-text .subtitle {
            font-weight: 700;
            font-size: 13px;
        }

        .header-text .address {
            font-size: 10px;
        }

        .logo-placeholder {
            width: 42px;
            height: 42px;
            border: 1px solid #666;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .title-bar {
            border: 1px solid #444;
            font-weight: 700;
            font-size: 13px;
            text-align: center;
            padding: 2px 8px;
            margin: 6px 0 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10.5px;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .info-table .label {
            width: 30%;
            white-space: nowrap;
        }

        .info-table .colon {
            width: 8px;
        }

        .info-table .value {
            font-weight: 700;
        }

        .reg-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 10.5px;
        }

        .reg-table td {
            border: 1px solid #444;
            padding: 3px 5px;
        }

        .reg-table .label {
            width: 38%;
        }

        .photo-card {
            width: 100%;
            border-collapse: collapse;
        }

        .photo-card td {
            vertical-align: top;
        }

        .photo-box {
            width: 92px;
            min-width: 92px;
            height: 112px;
            border: 1px solid #444;
            overflow: hidden;
            text-align: center;
            background: #fafafa;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #777;
        }

        .ident-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        .ident-table td {
            padding: 1px 4px;
            vertical-align: top;
        }

        .ident-table .label {
            width: 30%;
            white-space: nowrap;
        }

        .ident-table .colon {
            width: 8px;
        }

        .ident-table .value {
            font-weight: 700;
        }

        .signature-note {
            margin-top: 6px;
            font-size: 10px;
            line-height: 1.35;
        }

        .bottom-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0;
        }

        .bottom-grid td {
            vertical-align: top;
        }

        .bottom-left,
        .bottom-right {
            border: 1px solid #444;
            padding: 8px;
        }

        .bottom-left {
            width: 49%;
        }

        .bottom-right {
            width: 49%;
        }

        .ketentuan-list {
            margin: 0;
            padding-left: 18px;
            line-height: 1.45;
        }

        .laporan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-top: 0;
        }

        .laporan-table th,
        .laporan-table td {
            border: 1px solid #444;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }

        .laporan-table th {
            font-weight: 700;
        }

        .foot-box {
            margin-top: 26px;
            border: 1px solid #444;
            padding: 10px 12px;
            min-height: 78px;
        }

        .foot-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .foot-label {
            width: 110px;
            font-weight: 700;
        }

        .foot-line {
            flex: 1;
            border-bottom: 1px solid #444;
            min-height: 18px;
            padding: 0 4px 2px 4px;
            font-weight: 700;
        }

        .top-note {
            font-size: 9.5px;
            color: #666;
            margin-top: 4px;
            line-height: 1.35;
        }

        .signature-name {
            margin-top: 6px;
            font-weight: 700;
            text-align: center;
            line-height: 1.35;
        }

        .qr-placeholder {
            width: 58px;
            height: 58px;
            border: 1px solid #444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            margin: 2px auto 4px;
        }

        @media print {
            body {
                background: #fff;
            }

            .no-print {
                display: none !important;
            }

            .page {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            .panel,
            .bottom-left,
            .bottom-right {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    @php
    $pendidikans = $kartuAk1->riwayatPendidikan ?? collect();
    $pengalamans = $kartuAk1->pengalamanKerja ?? collect();
    $skills = $kartuAk1->keterampilan ?? collect();

    $namaLengkap = $profil->nama_lengkap ?? '-';
    $tempatLahir = $profil->tempat_lahir ?? '-';
    $tglLahir = $profil->tanggal_lahir
    ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d F Y')
    : '-';

    $alamatLengkap = trim(
    ($profil->alamat ?? '-') . ', RT ' . ($profil->rt ?? '-') . '/RW ' . ($profil->rw ?? '-') . ', ' .
    ($profil->kelurahan ?? '-') . ', ' . ($profil->kecamatan ?? '-') . ', ' .
    ($profil->kab_kota ?? '-') . ', ' . ($profil->provinsi ?? '-')
    );

    $pendidikanPertama = $pendidikans->first();
    $pendidikanLainnya = $pendidikans->skip(1);

    $pengantarNama = $kartuAk1->nama_petugas ?? ($kartuAk1->latestVerifikasi->nama_petugas ?? '-');
    $pengantarNip = $kartuAk1->nip_petugas ?? ($kartuAk1->latestVerifikasi->nip_petugas ?? '-');

    $tanggalPengantar = $kartuAk1->latestVerifikasi->tanggal_verifikasi
    ?? $kartuAk1->submitted_at
    ?? $kartuAk1->tanggal_daftar
    ?? null;

    $tanggalPengantarText = $tanggalPengantar
    ? \Carbon\Carbon::parse($tanggalPengantar)->format('d/m/Y')
    : '-';


    // ================= LAPORAN AUTO 6 BULAN =================
    $tanggalMulai = $kartuAk1->berlaku_mulai
    ? \Carbon\Carbon::parse($kartuAk1->berlaku_mulai)
    : null;

    $tanggalSelesai = $kartuAk1->berlaku_sampai
    ? \Carbon\Carbon::parse($kartuAk1->berlaku_sampai)
    : null;

    $laporanRows = [];

    if ($tanggalMulai && $tanggalSelesai) {

    $labels = ['PERTAMA', 'KEDUA', 'KETIGA', 'KEEMPAT', 'KELIMA', 'KEENAM'];

    $i = 0;
    $current = $tanggalMulai->copy();

    while ($current->lte($tanggalSelesai)) {

    $laporanRows[] = [
    'label' => $labels[$i] ?? 'KE-' . ($i + 1),
    'tanggal' => $current->format('d/m/Y')
    ];

    $current->addMonths(6);
    $i++;
    }
    }
    @endphp

    <div class="no-print">
        <a href="{{ route('pencaker.ak1.index') }}" class="btn">
            Kembali
        </a>

        <button type="button" onclick="window.print()" class="btn btn-primary">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="page">

        {{-- ================== BLOK ATAS ================== --}}
        <table class="panel" cellspacing="0" cellpadding="0">
            <tr>
                {{-- ================== KIRI ================== --}}
                <td class="left-col">
                    <div class="section-title">PENDIDIKAN FORMAL</div>

                    @forelse($pendidikans as $pendidikan)
                    <div class="item-row">
                        <div class="left">
                            {{ $pendidikan->nama_sekolah }}
                            @if($pendidikan->jurusan)
                            <span class="muted"> / {{ $pendidikan->jurusan }}</span>
                            @endif
                        </div>
                        <div class="right">TH. {{ $pendidikan->tahun_lulus ?? $pendidikan->tahun_masuk ?? '-' }}</div>
                    </div>
                    @empty
                    <div class="muted">-</div>
                    @endforelse

                    <div class="line"></div>

                    <div class="section-title">KETERAMPILAN</div>
                    @forelse($skills as $skill)
                    <div class="bullet">• {{ $skill->nama_keterampilan }} @if($skill->tingkat) <span class="muted">({{ $skill->tingkat }})</span> @endif</div>
                    @empty
                    <div class="muted">-</div>
                    @endforelse

                    <div class="line"></div>

                    <div class="section-title">PENGALAMAN</div>
                    @forelse($pengalamans as $pengalaman)
                    <div class="bullet">
                        • {{ $pengalaman->nama_perusahaan }}
                        @if($pengalaman->jabatan)
                        - {{ $pengalaman->jabatan }}
                        @endif
                    </div>
                    <div class="bullet muted" style="margin-left: 24px;">
                        {{ $pengalaman->mulai_bekerja ? \Carbon\Carbon::parse($pengalaman->mulai_bekerja)->format('d/m/Y') : '-' }}
                        s/d
                        {{ $pengalaman->selesai_bekerja ? \Carbon\Carbon::parse($pengalaman->selesai_bekerja)->format('d/m/Y') : '-' }}
                    </div>
                    @empty
                    <div class="muted">-</div>
                    @endforelse

                    <div class="line"></div>

                    <div class="section-title">PENGANTAR KERJA</div>

                    <div class="qr-placeholder" style="border:none;">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode(route('pencaker.ak1.cetak', $kartuAk1->id_kartu_ak1)) }}"
                            alt="QR Code">
                    </div>

                    <div class="signature-name">
                        {{ $pengantarNama }}<br>
                        <span style="font-weight: 700;">NIP. {{ $pengantarNip }}</span>
                    </div>

                    <div class="top-note">
                        *Dokumen ini telah ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE-BSSN).
                    </div>
                </td>

                {{-- ================== KANAN ================== --}}
                <td class="right-col">
                    <div class="header-flex">

                        {{-- LOGO --}}
                        <div class="header-logo">
                            <img src="{{ asset('images/Lambang_Kota_Jayapura.jpeg') }}" alt="Logo">
                        </div>

                        {{-- TEKS --}}
                        <div class="header-text">
                            <div class="title">PEMERINTAH KOTA JAYAPURA</div>
                            <div class="subtitle">DINAS TENAGA KERJA</div>
                            <div class="address">Jl. Samratulangi No.25, Mandala, Jayapura Utara</div>
                        </div>

                    </div>

                    <div class="title-bar">KARTU TANDA PENCARI KERJA</div>

                    <table class="reg-table">
                        <tr>
                            <td class="label">No. Pendaftaran Pencari Kerja</td>
                            <td>
                                {{ $kartuAk1->nomor_pendaftaran ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Nomor Induk Kependudukan</td>
                            <td>{{ $profil->nik ?? '-' }}</td>
                        </tr>
                    </table>

                    <table class="photo-card" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width: 104px; padding-right: 8px;">
                                <div class="photo-box">
                                    @if($profil->foto)
                                    <img src="{{ asset('storage/'.$profil->foto) }}" alt="Foto">
                                    @else
                                    <div class="photo-placeholder">FOTO</div>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <table class="ident-table">
                                    <tr>
                                        <td class="label">Nama Lengkap</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ strtoupper($namaLengkap) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Tempat, Tgl. Lahir</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ strtoupper($tempatLahir) }}, {{ strtoupper($tglLahir) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Jenis Kelamin</td>
                                        <td class="colon">:</td>
                                        <td class="value">
                                            {{
                                                $profil->jenis_kelamin == 'L' ? 'LAKI-LAKI' :
                                                ($profil->jenis_kelamin == 'P' ? 'PEREMPUAN' : '-')
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Status</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ strtoupper($profil->status_perkawinan ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Agama</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ strtoupper($profil->agama ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Alamat</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ strtoupper($alamatLengkap) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Berlaku s.d</td>
                                        <td class="colon">:</td>
                                        <td class="value">{{ $kartuAk1->berlaku_sampai ? \Carbon\Carbon::parse($kartuAk1->berlaku_sampai)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- ================== BLOK BAWAH ================== --}}
        <table class="bottom-grid" cellspacing="0" cellpadding="0">
            <tr>
                {{-- KETENTUAN BERLAKU --}}
                <td class="bottom-left">
                    <div class="section-title">KETENTUAN BERLAKU</div>

                    <ol class="ketentuan-list">
                        <li>Berlaku Nasional.</li>
                        <li>Bila ada perubahan data/keterangan lainnya atau telah mendapatkan pekerjaan agar segera laporan.</li>
                        <li>Apabila pencari kerja yang bersangkutan telah diterima bekerja maka instansi/perusahaan yang menerima agar memberitahukan AK/I ini ke Dinas Tenaga Kerja.</li>
                        <li>Kartu ini berlaku selama 2 tahun dengan keharusan melaporkan setiap 6 bulan sekali bagi pencari kerja yang belum mendapatkan pekerjaan.</li>
                    </ol>
                </td>

                <td style="width:2%;"></td>

                {{-- LAPORAN + TTD --}}
                <td class="bottom-right">
                    <table class="laporan-table">
                        <tr>
                            <th style="width: 20%;">LAPORAN</th>
                            <th style="width: 26%;">TGL-BLN-THN</th>
                            <th>Tanda Tangan Pengantar Kerja/Petugas Pendaftar (Cantumkan Nama dan NIP)</th>
                        </tr>

                        @foreach($laporanRows as $row)
                        <tr>
                            <td style="font-weight:700;">{{ $row['label'] }}</td>
                            <td>{{ $row['tanggal'] ?? '' }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach
                    </table>

                    <div class="foot-box">
                        <div class="foot-row">
                            <div class="foot-label">Diterima Kerja</div>
                            <div class="foot-line">
                                {{ $diterimaKerjaText ?? '-' }}
                            </div>
                        </div>
                        <div class="foot-row">
                            <div class="foot-label">Terhitung Tanggal</div>
                            <div class="foot-line">
                                {{ $terhitungTanggalText ?? '-' }}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function() {
            // aktifkan ini kalau mau auto buka dialog print:
            // window.print();
        };
    </script>
</body>

</html>