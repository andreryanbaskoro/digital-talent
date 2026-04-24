<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0 font-weight-bold">
                Data Laporan Penempatan Tenaga Kerja
            </h5>
        </div>
    </div>

    <div class="card-body pt-2">

        <div class="table-responsive">

            <table id="table-1"
                class="table table-hover table-borderless align-middle">

                <thead class="bg-light text-center">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Pencari Kerja</th>
                        <th>Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Tanggal Lamar</th>
                        <th>Tanggal Diterima</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($penempatan as $item)
                    <tr
                        class="border-bottom"
                        data-status="{{ strtolower($item->status_lamaran ?? '') }}"
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- NO --}}
                        <td class="text-center text-muted"></td>

                        {{-- PENCARI KERJA --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold text-dark">
                                    {{ $item->pencariKerja->nama_lengkap ?? '-' }}
                                </span>

                                <small class="text-muted">
                                    #{{ $item->id_pencari_kerja }}
                                </small>

                                <small class="text-primary">
                                    NIK: {{ $item->pencariKerja->nik ?? '-' }}
                                </small>
                            </div>
                        </td>

                        {{-- LOWONGAN --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->lowongan->judul_lowongan ?? '-' }}
                            </div>

                            <div class="text-muted small">
                                {{ $item->lowongan->lokasi ?? '-' }}
                            </div>
                        </td>

                        {{-- PERUSAHAAN --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ $item->lowongan->profilPerusahaan->kab_kota ?? '-' }}
                            </small>
                        </td>

                        {{-- TANGGAL LAMAR --}}
                        <td class="text-center">
                            {{ optional($item->tanggal_lamar)->format('d M Y') ?? '-' }}
                        </td>

                        {{-- TANGGAL DITERIMA --}}
                        <td class="text-center">
                            @if(strtolower($item->status_lamaran ?? '') === 'diterima')
                            <span class="text-success font-weight-bold">
                                {{ optional($item->updated_at)->format('d M Y') ?? '-' }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">

                            @if($item->deleted_at)
                            <span class="badge badge-dark px-3 py-2">
                                🗑 Terhapus
                            </span>
                            @else
                            @php
                            $status = strtolower($item->status_lamaran ?? 'diproses');
                            @endphp

                            @if($status === 'diterima')
                            <span class="badge badge-success px-3 py-2">
                                ✔ Diterima
                            </span>

                            @elseif($status === 'ditolak')
                            <span class="badge badge-danger px-3 py-2">
                                ✖ Ditolak
                            </span>

                            @elseif($status === 'diproses')
                            <span class="badge badge-warning px-3 py-2">
                                ⏳ Diproses
                            </span>

                            @else
                            <span class="badge badge-secondary px-3 py-2">
                                {{ ucfirst($status) }}
                            </span>
                            @endif
                            @endif

                            <div class="small text-muted mt-1">
                                Dibuat:
                                {{ optional($item->created_at)->format('d M Y') }}
                            </div>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-briefcase fa-2x mb-2"></i>
                            <div>Tidak ada data penempatan</div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>