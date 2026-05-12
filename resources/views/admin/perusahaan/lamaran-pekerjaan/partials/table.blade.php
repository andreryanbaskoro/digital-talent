@php
$uiLabel = [
'dikirim' => 'Lamaran Masuk',
'diproses' => 'Dalam Review',
'diterima' => 'Lolos Seleksi',
'ditolak' => 'Tidak Lolos Seleksi',
];

$badge = [
'dikirim' => 'primary',
'diproses' => 'warning',
'diterima' => 'success',
'ditolak' => 'dark',
];
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="table-1" class="table table-hover table-striped mb-0">

                <thead class="bg-light text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Pelamar</th>
                        <th>Lowongan</th>
                        <th>Lamaran</th>
                        <th style="width:140px;">Status</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lamaran as $item)
                    <tr data-status="{{ $item->status_lamaran }}"
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- NO --}}
                        <td class="text-center align-middle">
                            {{ $loop->iteration }}
                        </td>

                        {{-- PELAMAR --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->pencariKerja->nama_lengkap ?? '-' }}
                            </div>

                            <small class="text-muted d-block">
                                NIK: {{ $item->pencariKerja->nik ?? '-' }}
                            </small>

                            <small class="text-muted d-block">
                                HP: {{ $item->pencariKerja->nomor_hp ?? '-' }}
                            </small>

                            <small class="text-muted d-block">
                                Email: {{ $item->pencariKerja->email ?? '-' }}
                            </small>
                        </td>

                        {{-- LOWONGAN --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->lowongan->judul_lowongan ?? '-' }}
                            </div>

                            <small class="text-muted d-block">
                                📍 {{ $item->lowongan->lokasi ?? '-' }}
                            </small>

                            <small class="text-muted d-block">
                                {{ $item->lowongan->jenis_pekerjaan ?? '-' }} •
                                {{ $item->lowongan->sistem_kerja ?? '-' }}
                            </small>

                            <small class="text-muted d-block">
                                Kuota: {{ $item->lowongan->kuota ?? '-' }}
                            </small>
                        </td>

                        {{-- INFO LAMARAN --}}
                        <td>
                            <small class="text-muted d-block">
                                ID: {{ $item->id_lamaran }}
                            </small>

                            <small class="text-muted d-block">
                                Tanggal: {{ optional($item->tanggal_lamar)->format('d-m-Y') ?? '-' }}
                            </small>

                            <small class="text-muted d-block">
                                Dokumen: {{ $item->dokumen->count() }} file
                            </small>

                            @if($item->hasilPerhitungan)
                            <small class="text-success d-block">
                                Nilai: {{ number_format($item->hasilPerhitungan->nilai_total,2) }}
                            </small>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center align-middle">
                            @if($item->deleted_at)
                            <span class="badge badge-danger px-3 py-2">
                                Terhapus
                            </span>
                            @else
                            <span class="px-2 py-1 text-{{ $badge[$item->status_lamaran] ?? 'secondary' }}">
                                {{ $uiLabel[$item->status_lamaran] ?? ucfirst($item->status_lamaran) }}
                            </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center align-middle">

                            <!-- PROSES HITUNG (AI / RANKING) -->
                            <a href="javascript:void(0)"
                                class="btn btn-sm btn-primary btn-hit-detail mb-1"
                                data-url="{{ route('perusahaan.ranking.detail', [$item->id_lowongan, $item->id_lamaran]) }}">
                                <i class="fas fa-calculator mr-1"></i> Proses Hitung Ranking
                            </a>

                            <!-- LIHAT DETAIL LAMARAN -->
                            <button type="button"
                                class="btn btn-sm btn-outline-info btn-show mb-1"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.show', $item->id_lamaran) }}">
                                <i class="fas fa-eye mr-1"></i> Lihat Detail Lamaran
                            </button>

                            @if($item->deleted_at)

                            <!-- RESTORE -->
                            <button type="button"
                                class="btn btn-sm btn-success btn-restore mb-1"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.restore', $item->id_lamaran) }}">
                                <i class="fas fa-undo mr-1"></i> Pulihkan Data
                            </button>

                            <!-- FORCE DELETE -->
                            <button type="button"
                                class="btn btn-sm btn-danger btn-force-delete mb-1"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.forceDelete', $item->id_lamaran) }}">
                                <i class="fas fa-times mr-1"></i> Hapus Permanen
                            </button>

                            @else

                            <!-- DELETE -->
                            <button type="button"
                                class="btn btn-sm btn-danger btn-hapus mb-1"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.destroy', $item->id_lamaran) }}">
                                <i class="fas fa-trash mr-1"></i> Hapus Lamaran
                            </button>

                            @endif

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada data lamaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAI" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-body p-5">

                {{-- HEADER --}}
                <div class="text-center mb-4">
                    <div class="spinner-grow text-primary mb-3" style="width:3rem;height:3rem;"></div>

                    <h4 class="font-weight-bold">
                        Perhitungan Ranking
                    </h4>

                    <p class="text-muted mb-0">
                        Sistem Profile Matching
                    </p>
                </div>

                {{-- STEP BOX --}}
                <div class="border rounded p-3 bg-light mb-3">

                    <div class="step-title font-weight-bold text-primary mb-2">
                        Inisialisasi Sistem...
                    </div>

                    <div class="step-desc text-muted small">
                        Memuat data kandidat dan parameter lowongan pekerjaan
                    </div>

                </div>

                {{-- PROGRESS --}}
                <div class="progress mb-3" style="height:10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        style="width:0%"></div>
                </div>

                {{-- MATRIX SIMULASI --}}
                <div class="text-center text-muted small matrix-log">
                    Menunggu proses perhitungan...
                </div>

            </div>

        </div>
    </div>
</div>