@php
$statusLabel = [
'dikirim' => 'Diproses',
'diproses' => 'Diproses',
'diterima' => 'Diterima',
'ditolak' => 'Ditolak',
];

$statusBadge = [
'dikirim' => 'warning',
'diproses' => 'warning',
'diterima' => 'success',
'ditolak' => 'dark',
];
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table table-bordered table-hover table-striped mb-0 align-middle text-center">

                <thead class="bg-light">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th style="width:80px;">Rank</th>
                        <th class="text-left">Pelamar</th>
                        <th class="text-left">Lowongan</th>
                        <th>Nilai</th>
                        <th>%</th>
                        <th>Rekomendasi</th>
                        <th>Status</th>
                        <th class="text-left">Catatan</th>
                        <th>Dokumen</th>
                        <th>Tanggal</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lamaran as $item)

                    @php
                    $hasil = $item->hasilPerhitungan;

                    $rank = $hasil->peringkat ?? null;
                    $nilai = $hasil->nilai_total ?? null;

                    // ✅ 1 angka di belakang koma
                    $persen = is_numeric($nilai)
                    ? number_format(($nilai / 5) * 100, 1, '.', '')
                    : null;

                    // kategori
                    if (is_null($persen)) {
                    $rekom = '-';
                    } elseif ($persen >= 85) {
                    $rekom = '⭐ Sangat Cocok';
                    } elseif ($persen >= 70) {
                    $rekom = '👍 Cocok';
                    } else {
                    $rekom = '❗ Kurang Cocok';
                    }
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        {{-- RANK --}}
                        <td>
                            @if($rank == 1) 🥇
                            @elseif($rank == 2) 🥈
                            @elseif($rank == 3) 🥉
                            @endif
                            {{ $rank ?? '-' }}
                        </td>

                        {{-- PELAMAR --}}
                        <td class="text-left">
                            <b>{{ $item->pencariKerja->nama_lengkap ?? '-' }}</b>
                            <small class="d-block text-muted">
                                {{ $item->pencariKerja->email ?? '-' }}
                            </small>
                        </td>

                        {{-- LOWONGAN --}}
                        <td class="text-left">
                            <b>{{ $item->lowongan->judul_lowongan ?? '-' }}</b>
                            <small class="d-block text-muted">
                                {{ $item->lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}
                            </small>
                        </td>

                        {{-- NILAI --}}
                        <td>{{ $nilai ? number_format($nilai,2) : '-' }}</td>

                        {{-- PERSEN --}}
                        <td>
                            {{ $persen !== null 
        ? rtrim(rtrim($persen, '0'), '.') . '%' 
        : '-' 
    }}
                        </td>

                        {{-- REKOM --}}
                        <td>
                            <span class="badge badge-info">{{ $rekom }}</span>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge badge-{{ $statusBadge[$item->status_lamaran] ?? 'secondary' }}">
                                {{ $statusLabel[$item->status_lamaran] ?? '-' }}
                            </span>
                        </td>

                        {{-- CATATAN --}}
                        <td class="text-left">
                            {{ $item->catatan_perusahaan ?? '-' }}
                        </td>

                        {{-- DOKUMEN --}}
                        <td>{{ $item->dokumen->count() }}</td>

                        {{-- TANGGAL --}}
                        <td>
                            {{ optional($item->tanggal_lamar)->format('d-m-Y') }}
                        </td>

                        {{-- AKSI --}}
                        <td>
                            @php
                            $status = $item->status_lamaran;
                            @endphp

                            {{-- TERIMA --}}
                            <button
                                class="btn btn-sm btn-keputusan {{ $status == 'diterima' ? 'btn-success' : 'btn-outline-success' }} mr-1"
                                data-action="{{ route('perusahaan.keputusan-seleksi.terima', $item->id_lamaran) }}"
                                data-title="Terima"
                                data-status="diterima"
                                title="Terima">
                                <i class="fas fa-check"></i>
                            </button>

                            {{-- TOLAK --}}
                            <button
                                class="btn btn-sm btn-keputusan {{ $status == 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }} mr-2"
                                data-action="{{ route('perusahaan.keputusan-seleksi.tolak', $item->id_lamaran) }}"
                                data-title="Tolak"
                                data-status="ditolak"
                                title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>

                            {{-- DETAIL --}}
                            <a
                                href="{{ route('perusahaan.ranking.detail', [$lowongan->id_lowongan, $item->id_lamaran]) }}"
                                class="btn btn-outline-info btn-sm"
                                title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>