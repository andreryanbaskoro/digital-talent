@extends('layouts.app-admin')

@section('title', $title ?? 'Detail Perhitungan')

@section('content')
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="mb-0">Detail Perhitungan Profile Matching</h1>
                    <small class="text-muted">
                        {{ $lowongan->judul_lowongan ?? '-' }} -
                        {{ $lamaran->pencariKerja->nama_lengkap ?? '-' }}
                    </small>
                </div>

                <a href="{{ route('perusahaan.ranking.show', $lowongan->id_lowongan) }}"
                    class="btn btn-outline-secondary">
                    ← Kembali
                </a>
            </div>
        </div>
    </section>

    @php
    $skill = (float) ($live['skill'] ?? 0);
    $pengalaman = (float) ($live['pengalaman'] ?? 0);
    $pendidikan = (float) ($live['pendidikan'] ?? 0);
    $lokasi = (float) ($live['lokasi'] ?? 0);

    $nilaiAkhirSistem = (float) ($live['total_nilai'] ?? 0);
    $persentaseSistem = (float) ($live['persentase'] ?? round(($nilaiAkhirSistem / 5) * 100, 2));

    $rekomendasiSistem = $hasil->rekomendasi
    ?? ($persentaseSistem >= 85 ? '⭐ Sangat Cocok' : ($persentaseSistem >= 70 ? '👍 Cocok' : '❗ Kurang Cocok'));

    $skillCollection = collect($skillRows);
    $skillTotalBobot = $skillCollection->sum('bobot_selisih');
    $skillJumlahSub = $skillCollection->count();
    $skillNilaiAkhir = $skillJumlahSub > 0 ? round($skillTotalBobot / $skillJumlahSub, 2) : 0;

    $skillBobotList = $skillCollection
    ->pluck('bobot_selisih')
    ->map(fn($v) => number_format((float) $v, 2, '.', ''))
    ->implode(' + ');

    $gapTable = [
    ['gap' => '0', 'bobot' => '5'],
    ['gap' => '1', 'bobot' => '4.5'],
    ['gap' => '-1', 'bobot' => '4'],
    ['gap' => '2', 'bobot' => '3.5'],
    ['gap' => '-2', 'bobot' => '3'],
    ['gap' => '3', 'bobot' => '2.5'],
    ['gap' => '-3', 'bobot' => '2'],
    ['gap' => '≥4 atau ≤-4', 'bobot' => '1'],
    ];

    $auditRows = collect([
    ['nama' => 'Skill', 'nilai' => $skill, 'target' => 5],
    ['nama' => 'Pengalaman', 'nilai' => $pengalaman, 'target' => 5],
    ['nama' => 'Pendidikan', 'nilai' => $pendidikan, 'target' => 5],
    ['nama' => 'Lokasi', 'nilai' => $lokasi, 'target' => 5],
    ])->map(function ($item) {
    $gap = abs((float) $item['target'] - (float) $item['nilai']);
    $gapInt = (int) round($gap);

    $item['gap'] = round($gap, 2);
    $item['skor'] = match (true) {
    $gapInt <= 0=> 5,
        $gapInt == 1 => 4,
        $gapInt == 2 => 3,
        $gapInt == 3 => 2,
        default => 1,
        };

        return $item;
        });

        $auditTotal = $auditRows->sum('skor');
        $auditMaksimal = 20;
        $auditPersentase = $auditMaksimal > 0 ? round(($auditTotal / $auditMaksimal) * 100, 2) : 0;

        $auditKategori = match (true) {
        $auditPersentase >= 85 => '⭐ Sangat Cocok',
        $auditPersentase >= 70 => '👍 Cocok',
        default => '❗ Kurang Cocok',
        };
        @endphp

        @php
        function formatAngka($angka, $digit = 2) {
        return is_numeric($angka)
        ? rtrim(rtrim(number_format($angka, $digit), '0'), '.')
        : '-';
        }
        @endphp

        <section class="content">
            <div class="container-fluid">

                <div class="row mb-3">

                    {{-- RANK --}}
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body">
                                <small class="text-muted">
                                    <i class="fas fa-trophy"></i> Ranking
                                </small>
                                <h3 class="mb-0 font-weight-bold">
                                    {{ $hasil->peringkat ?? '-' }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- NILAI --}}
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body">
                                <small class="text-muted">
                                    <i class="fas fa-star"></i> Nilai (0–5)
                                </small>
                                <h3 class="mb-0 text-primary font-weight-bold">
                                    {{ formatAngka($nilaiAkhirSistem) }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- PERSEN --}}
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body">
                                <small class="text-muted">
                                    <i class="fas fa-chart-line"></i> Persentase
                                </small>
                                <h3 class="mb-0 text-success font-weight-bold">
                                    {{ formatAngka($persentaseSistem,1) }}%
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- REKOMENDASI --}}
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center h-100">
                            <div class="card-body">
                                <small class="text-muted">
                                    <i class="fas fa-check-circle"></i> Rekomendasi
                                </small>
                                <div class="mt-2">
                                    <span class="badge badge-info px-3 py-2">
                                        {{ $rekomendasiSistem }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="alert alert-secondary shadow-sm">
                    <b>Keterangan warna:</b><br>
                    <span class="badge badge-primary">Biru</span> = nilai pelamar<br>
                    <span class="badge badge-warning">Kuning</span> = target perusahaan<br>
                    <span class="badge badge-danger">Merah</span> = GAP / selisih<br>
                    <span class="badge badge-success">Hijau</span> = bobot / skor hasil konversi
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <strong>Data Kandidat</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><b>Nama:</b> {{ $lamaran->pencariKerja->nama_lengkap ?? '-' }}</p>
                                <p class="mb-1"><b>Lowongan:</b> {{ $lowongan->judul_lowongan ?? '-' }}</p>
                                <p class="mb-1"><b>Perusahaan:</b> {{ $lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><b>ID Lamaran:</b> {{ $lamaran->id_lamaran }}</p>
                                <p class="mb-1"><b>ID Hasil:</b> {{ $hasil->id_hasil ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body text-center bg-light rounded">
                            <strong>Alur Perhitungan:</strong><br>
                            Target → Hitung GAP (|T − A|) → Konversi ke Skor Profil →
                            Hitung Nilai Tiap Kriteria → Total → Persentase → Kategori
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <strong>Asumsi dan Aturan</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border bg-light h-100">
                                    <h6 class="mb-2">Skala dan Persentase</h6>
                                    <div>Skala tiap kriteria: <b>1–5</b></div>
                                    <div>Persentase matching: <b>(total skor / 20) × 100%</b></div>
                                    <div class="mt-2">
                                        Kategori:<br>
                                        ≥ 85% → <b>Sangat Cocok ⭐</b><br>
                                        70%–84.99% → <b>Cocok 👍</b><br>
                                        &lt; 70% → <b>Kurang Cocok ❗</b>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border bg-light h-100">
                                    <h6 class="mb-2">Rumus Umum</h6>
                                    <div>GAP = |Target (T) − Nilai pelamar| per kriteria</div>
                                    <div>Konversi GAP → skor profil</div>
                                    <div>Maksimum total skor = <b>5 × 4 kriteria = 20</b></div>
                                    <div>Skor akhir digunakan untuk menghasilkan persentase matching.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <strong>Dasar Rumus Profile Matching</strong>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border bg-light h-100">
                                    <h6 class="mb-2">1. Perhitungan GAP</h6>
                                    <div class="mb-2"><b>GAP = A − T</b></div>
                                    <small class="text-muted">
                                        A = nilai aktual kandidat<br>
                                        T = target perusahaan
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border bg-light h-100">
                                    <h6 class="mb-2">2. Konversi GAP ke Bobot Nilai</h6>
                                    <div class="mb-2"><b>Bobot = hasil konversi dari selisih GAP</b></div>
                                    <small class="text-muted">
                                        GAP yang semakin kecil terhadap target akan menghasilkan skor yang semakin tinggi.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>GAP</th>
                                        <th>Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gapTable as $row)
                                    <tr>
                                        <td>{{ $row['gap'] }}</td>
                                        <td><b>{{ $row['bobot'] }}</b></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 p-3 bg-light border rounded">
                            <strong>Rumus konversi:</strong><br>
                            GAP 0 → 5<br>
                            GAP 1 → 4.5<br>
                            GAP -1 → 4<br>
                            GAP 2 → 3.5<br>
                            GAP -2 → 3<br>
                            GAP 3 → 2.5<br>
                            GAP -3 → 2<br>
                            GAP ≥ 4 atau ≤ -4 → 1
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <strong>Langkah 1 — Target Skill dari Perusahaan</strong>
                        <span class="badge badge-warning">Data Acuan</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-left">Nama Skill</th>
                                    <th>Target Perusahaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($skillRows as $item)
                                <tr>
                                    <td class="text-left">{{ $item['nama_skill'] }}</td>
                                    <td><span class="badge badge-warning">{{ $item['nilai_target'] }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-muted">Tidak ada skill yang disyaratkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 p-3 bg-light border rounded">
                            <strong>Rumus:</strong><br>
                            Target Skill = nilai target yang ditetapkan perusahaan untuk setiap sub-kriteria.
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <strong>Langkah 2 — Nilai Pelamar, GAP, dan Bobot</strong>
                        <span class="badge badge-success">GAP → Skor</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead>
                                <tr>
                                    <th class="text-left">Nama Skill</th>
                                    <th class="bg-primary text-white">Nilai Pelamar</th>
                                    <th class="bg-warning text-dark">Target</th>
                                    <th class="bg-danger text-white">GAP</th>
                                    <th class="bg-success text-white">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($skillRows as $item)
                                <tr>
                                    <td class="text-left">{{ $item['nama_skill'] }}</td>
                                    <td><span class="badge badge-primary">{{ $item['nilai_pelamar'] }}</span></td>
                                    <td><span class="badge badge-warning">{{ $item['nilai_target'] }}</span></td>
                                    <td><span class="badge badge-danger">{{ $item['selisih'] }}</span></td>
                                    <td><span class="badge badge-success">{{ $item['bobot_selisih'] }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Tidak ada data perbandingan skill.</td>
                                </tr>
                                @endforelse
                            </tbody>

                            @if($skillJumlahSub > 0)
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="4" class="text-right">Total Bobot Sub-Skill</th>
                                    <th>{{ $skillTotalBobot }}</th>
                                </tr>
                                <tr class="table-light">
                                    <th colspan="4" class="text-right">Jumlah Sub-Skill</th>
                                    <th>{{ $skillJumlahSub }}</th>
                                </tr>
                                <tr class="table-info">
                                    <th colspan="5" class="text-center">
                                        <strong>
                                            Nilai Skill = ( {{ $skillBobotList }} ) / {{ $skillJumlahSub }}
                                            = {{ $skillNilaiAkhir }}
                                        </strong>
                                    </th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>

                        <div class="mt-3 p-3 bg-light border rounded">
                            <strong>Rumus:</strong><br>
                            GAP = |Target − Nilai Pelamar|<br>
                            Bobot = hasil konversi GAP pada tabel standar Profile Matching<br>
                            Nilai Skill = Total Bobot Sub-Skill / Jumlah Sub-Skill
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <strong>Langkah 3 — Nilai Kriteria Utama</strong>
                        <span class="badge badge-primary">Skill, Pengalaman, Pendidikan, Lokasi</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th class="bg-primary text-white">Nilai Pelamar</th>
                                    <th class="bg-warning text-dark">Target</th>
                                    <th class="bg-danger text-white">GAP</th>
                                    <th class="bg-success text-white">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detailPerhitungan->whereIn('jenis_kriteria', ['skill', 'pengalaman', 'pendidikan', 'lokasi']) as $item)
                                <tr>
                                    <td class="text-left">{{ strtoupper($item->jenis_kriteria) }}</td>
                                    <td><span class="badge badge-primary">{{ $item->nilai_pelamar }}</span></td>
                                    <td><span class="badge badge-warning">{{ $item->nilai_target }}</span></td>
                                    <td><span class="badge badge-danger">{{ $item->selisih }}</span></td>
                                    <td><span class="badge badge-success">{{ $item->bobot_selisih }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Tidak ada data kriteria utama.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 p-3 bg-light border rounded">
                            <strong>Rumus:</strong><br>
                            GAP = Nilai Pelamar − Target<br>
                            Bobot = hasil konversi GAP<br>
                            Nilai kriteria utama = skor yang sudah dikonversi dari GAP
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom">
                                <strong>Langkah 4 — Ringkasan Nilai Sistem</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded border bg-light text-center">
                                            <div class="text-muted">Skill</div>
                                            <h4 class="mb-0">{{ $skill }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded border bg-light text-center">
                                            <div class="text-muted">Pengalaman</div>
                                            <h4 class="mb-0">{{ $pengalaman }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded border bg-light text-center">
                                            <div class="text-muted">Pendidikan</div>
                                            <h4 class="mb-0">{{ $pendidikan }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded border bg-light text-center">
                                            <div class="text-muted">Lokasi</div>
                                            <h4 class="mb-0">{{ $lokasi }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-light border rounded">
                                    <strong>Rumus:</strong><br>
                                    Nilai setiap kriteria merupakan hasil skor akhir setelah konversi GAP.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom">
                                <strong>Langkah 5 — Hasil Akhir Sistem</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">Rumus total sistem:</p>
                                <p class="mb-2">
                                    <b>Total = (Skill × 0.4) + (Pengalaman × 0.3) + (Pendidikan × 0.2) + (Lokasi × 0.1)</b>
                                </p>
                                <p class="mb-2">
                                    = ({{ $skill }} × 0.4) + ({{ $pengalaman }} × 0.3) + ({{ $pendidikan }} × 0.2) + ({{ $lokasi }} × 0.1)
                                </p>
                                <p class="mb-2">
                                    = <b>{{ $nilaiAkhirSistem }}</b>
                                </p>

                                <hr>

                                <p class="mb-2">
                                    Persentase = (Total Nilai / 5) × 100%
                                </p>
                                <p class="mb-2">
                                    = ({{ $nilaiAkhirSistem }} / 5) × 100%
                                </p>
                                <h3 class="text-success mt-2">{{ $persentaseSistem }}%</h3>

                                <div class="mt-3 p-3 bg-light border rounded">
                                    <strong>Rumus:</strong><br>
                                    Persentase = (Total Nilai / 5) × 100%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <strong>Langkah 6 — Rekap Hitung</strong>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-left">Kriteria</th>
                                    <th>Nilai Aktual (A)</th>
                                    <th>Target (T)</th>
                                    <th>|T − A|</th>
                                    <th>Skor Profil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditRows as $row)
                                <tr>
                                    <td class="text-left">{{ $row['nama'] }}</td>
                                    <td><span class="badge badge-primary">{{ $row['nilai'] }}</span></td>
                                    <td><span class="badge badge-warning">{{ $row['target'] }}</span></td>
                                    <td><span class="badge badge-danger">{{ $row['gap'] }}</span></td>
                                    <td><span class="badge badge-success">{{ $row['skor'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="4" class="text-right">Total Skor</th>
                                    <th>{{ $auditTotal }}</th>
                                </tr>
                                <tr class="table-light">
                                    <th colspan="4" class="text-right">Skor Maksimal</th>
                                    <th>{{ $auditMaksimal }}</th>
                                </tr>
                                <tr class="table-info">
                                    <th colspan="5" class="text-center">
                                        <strong>
                                            Persentase Matching = ({{ $auditTotal }} / {{ $auditMaksimal }}) × 100% = {{ $auditPersentase }}%
                                        </strong>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-3 p-3 bg-light border rounded">
                            <strong>Rumus:</strong><br>
                            GAP = |Target − Nilai Aktual|<br>
                            Skor profil ditentukan dari tabel standar 5, 4, 3, 2, 1<br>
                            Persentase matching = (Total Skor / 20) × 100%
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <strong>Langkah 7 — Kesimpulan</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                @if($persentaseSistem >= 85)
                                <div class="alert alert-success mb-0">⭐ Sangat Cocok</div>
                                @elseif($persentaseSistem >= 70)
                                <div class="alert alert-primary mb-0">👍 Cocok</div>
                                @else
                                <div class="alert alert-danger mb-0">❗ Kurang Cocok</div>
                                @endif
                            </div>

                            <!-- <div class="col-md-6 mb-3">
                                @if($auditPersentase >= 85)
                                <div class="alert alert-success mb-0">Manual: ⭐ Sangat Cocok</div>
                                @elseif($auditPersentase >= 70)
                                <div class="alert alert-primary mb-0">Manual: 👍 Cocok</div>
                                @else
                                <div class="alert alert-danger mb-0">Manual: ❗ Kurang Cocok</div>
                                @endif
                            </div> -->
                        </div>

                        <div class="text-muted">
                            Semua angka di halaman ini ditampilkan berurutan agar mudah dicatat dan dihitung ulang secara manual.
                        </div>
                    </div>
                </div>

            </div>
        </section>
</div>
@endsection