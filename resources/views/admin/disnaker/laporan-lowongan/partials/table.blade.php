<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h5 class="mb-0 font-weight-bold">
                Data Laporan Lowongan
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
                        <th>Lowongan</th>
                        <th>Detail</th>
                        <th>Kualifikasi</th>
                        <th>Info Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($lowongan as $item)
                    <tr
                        class="border-bottom"
                        data-status="{{ $item->status }}"
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- NO --}}
                        <td class="text-center text-muted"></td>

                        {{-- LOWONGAN --}}
                        <td>
                            <div class="d-flex flex-column">

                                <span class="font-weight-bold text-dark">
                                    {{ $item->judul_lowongan }}
                                </span>

                                <small class="text-muted">
                                    {{ $item->id_lowongan }}
                                </small>

                                <small class="text-primary">
                                    {{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}
                                </small>

                            </div>
                        </td>

                        {{-- DETAIL --}}
                        <td>
                            <div class="mb-1">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ $item->lokasi ?? '-' }}
                            </div>

                            <span class="badge badge-light border">
                                {{ ucfirst($item->jenis_pekerjaan ?? '-') }}
                            </span>

                            <span class="badge badge-light border">
                                {{ ucfirst($item->sistem_kerja ?? '-') }}
                            </span>
                        </td>

                        {{-- KUALIFIKASI --}}
                        <td>
                            <div>🎓 {{ $item->pendidikan_minimum ?? '-' }}</div>
                            <small>💼 {{ $item->pengalaman_minimum ?? '-' }}</small>
                        </td>

                        {{-- INFO KERJA --}}
                        <td>
                            <div class="text-success font-weight-bold">
                                @if($item->gaji_minimum && $item->gaji_maksimum)
                                Rp{{ number_format($item->gaji_minimum, 0, ',', '.') }}
                                -
                                Rp{{ number_format($item->gaji_maksimum, 0, ',', '.') }}
                                @else
                                -
                                @endif
                            </div>

                            <small>
                                👥 {{ $item->kuota ?? '-' }} kandidat
                            </small>

                            <div class="text-muted small mt-1">
                                @if($item->tanggal_mulai && $item->tanggal_berakhir)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                →
                                {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}
                                @else
                                -
                                @endif
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">

                            @php
                            $badge = [
                            'pending' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            ];
                            @endphp

                            @if($item->deleted_at)
                            <span class="badge badge-dark px-3 py-2">
                                🗑 Terhapus
                            </span>
                            @else
                            <span class="badge badge-{{ $badge[$item->status] ?? 'secondary' }} px-3 py-2">
                                {{ ucfirst($item->status ?? '-') }}
                            </span>
                            @endif

                            <div class="mt-2 text-muted small">
                                {{ $item->catatan ?? '-' }}
                            </div>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <div>Tidak ada data lowongan</div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>