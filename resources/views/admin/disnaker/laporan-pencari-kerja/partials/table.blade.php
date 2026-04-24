<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h5 class="mb-0 font-weight-bold">
                Data Laporan Pencari Kerja
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
                        <th>Kontak</th>
                        <th>AK1</th>
                        <th>Lamaran</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pencariKerja as $item)
                    <tr
                        class="border-bottom"
                        data-ak1="{{ $item->kartuAk1 ? 1 : 0 }}"
                        data-lamaran="{{ $item->lamaranPekerjaan->count() > 0 ? 1 : 0 }}"
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- NO --}}
                        <td class="text-center text-muted"></td>

                        {{-- PENCARI KERJA --}}
                        <td>
                            <div class="d-flex flex-column">

                                <span class="font-weight-bold text-dark">
                                    {{ $item->nama_lengkap }}
                                </span>

                                <small class="text-muted">
                                    {{ $item->id_pencari_kerja }}
                                </small>

                                <small class="text-primary">
                                    NIK: {{ $item->nik ?? '-' }}
                                </small>

                            </div>
                        </td>

                        {{-- KONTAK --}}
                        <td>
                            <div>📞 {{ $item->nomor_hp ?? '-' }}</div>
                            <small>📧 {{ $item->email ?? '-' }}</small>

                            <div class="text-muted small mt-1">
                                {{ $item->kab_kota ?? '-' }}
                            </div>
                        </td>

                        {{-- AK1 --}}
                        <td class="text-center">

                            @if($item->kartuAk1)

                            <span class="badge badge-success px-3 py-2">
                                Aktif
                            </span>

                            <div class="small mt-1 text-muted">
                                {{ $item->kartuAk1->nomor_pendaftaran }}
                            </div>

                            <div class="small text-muted">
                                {{ optional($item->kartuAk1->berlaku_sampai)->format('d M Y') ?? '-' }}
                            </div>

                            @else
                            <span class="badge badge-secondary px-3 py-2">
                                Belum Ada
                            </span>
                            @endif

                        </td>

                        {{-- LAMARAN --}}
                        <td class="text-center">

                            @php
                            $totalLamaran = $item->lamaranPekerjaan->count();
                            @endphp

                            @if($totalLamaran > 0)

                            <span class="badge badge-info px-3 py-2">
                                {{ $totalLamaran }} Lamaran
                            </span>

                            <div class="small text-muted mt-1">
                                Terakhir:
                                {{ optional($item->lamaranPekerjaan->last())->tanggal_lamar?->format('d M Y') ?? '-' }}
                            </div>

                            @else
                            <span class="badge badge-light border px-3 py-2">
                                Belum Melamar
                            </span>
                            @endif

                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">

                            @if($item->deleted_at)
                            <span class="badge badge-dark px-3 py-2">
                                🗑 Terhapus
                            </span>
                            @else
                            <span class="badge badge-primary px-3 py-2">
                                Aktif
                            </span>
                            @endif

                            <div class="small text-muted mt-1">
                                Daftar:
                                {{ optional($item->created_at)->format('d M Y') }}
                            </div>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <div>Tidak ada data pencari kerja</div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>