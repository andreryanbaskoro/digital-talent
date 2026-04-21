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
                                Nilai: {{ number_format($item->hasilPerhitungan->nilai_akhir,2) }}
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
                            <span class="badge badge-{{ $badge[$item->status_lamaran] ?? 'secondary' }} px-3 py-2">
                                {{ $uiLabel[$item->status_lamaran] ?? ucfirst($item->status_lamaran) }}
                            </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center align-middle">

                            <button type="button"
                                class="btn btn-sm btn-info btn-show"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.show', $item->id_lamaran) }}">
                                <i class="fas fa-eye"></i>
                            </button>

                            @if($item->deleted_at)
                            <button type="button"
                                class="btn btn-success btn-sm btn-restore"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.restore', $item->id_lamaran) }}">
                                <i class="fas fa-undo"></i>
                            </button>

                            <button type="button"
                                class="btn btn-danger btn-sm btn-force-delete"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.forceDelete', $item->id_lamaran) }}">
                                <i class="fas fa-times"></i>
                            </button>
                            @else
                            <button type="button"
                                class="btn btn-danger btn-sm btn-hapus"
                                data-url="{{ route('perusahaan.lamaran-pekerjaan.destroy', $item->id_lamaran) }}">
                                <i class="fas fa-trash"></i>
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