<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h5 class="mb-0 font-weight-bold">
                Data Pelamar Perusahaan
            </h5>

        </div>
    </div>

    <div class="card-body pt-2">

        <div class="table-responsive">

            <table id="table-1"
                class="table table-hover table-bordered align-middle">

                <thead class="bg-light text-center">
                    <tr>
                        <th style="width:50px;">NO</th>
                        <th>Pelamar</th>
                        <th>Lowongan Dilamar</th>
                        <th>Tanggal Lamar</th>
                        <th>Kontak</th>
                        <th>Status Lamaran</th>
                        <th>Status Akun</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                    $nomor = 1;
                    @endphp

                    @forelse($pencariKerja as $item)

                    @php
                    $lamaranPerusahaan = $item->lamaranPekerjaan
                    ->filter(function ($lamaran) {

                    return optional($lamaran->lowongan)->id_perusahaan
                    === auth()->user()->profilPerusahaan->id_perusahaan;
                    });
                    @endphp

                    @foreach($lamaranPerusahaan as $lamaran)

                    <tr
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- NO --}}
                        <td class="text-center font-weight-bold">
                            {{ $nomor++ }}
                        </td>

                        {{-- PELAMAR --}}
                        <td>

                            <div class="d-flex flex-column">

                                <span class="font-weight-bold text-dark">
                                    {{ $item->nama_lengkap ?? '-' }}
                                </span>

                                <small class="text-muted">
                                    {{ $item->id_pencari_kerja }}
                                </small>

                                <small class="text-primary">
                                    NIK:
                                    {{ $item->nik ?? '-' }}
                                </small>

                            </div>

                        </td>

                        {{-- LOWONGAN --}}
                        <td>

                            <div class="d-flex flex-column">

                                <span class="font-weight-bold">
                                    {{ optional($lamaran->lowongan)->judul_lowongan ?? '-' }}
                                </span>

                                <small class="text-muted">
                                    {{ optional($lamaran->lowongan)->lokasi ?? '-' }}
                                </small>

                                <small class="text-info">
                                    {{ ucfirst(optional($lamaran->lowongan)->jenis_pekerjaan ?? '-') }}
                                </small>

                            </div>

                        </td>

                        {{-- TANGGAL LAMAR --}}
                        <td class="text-center">

                            <div class="font-weight-bold">
                                {{ optional($lamaran->tanggal_lamar)->format('d M Y') ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ optional($lamaran->tanggal_lamar)->format('H:i') ?? '' }}
                            </small>

                        </td>

                        {{-- KONTAK --}}
                        <td>

                            <div>
                                📞 {{ $item->nomor_hp ?? '-' }}
                            </div>

                            <small>
                                📧 {{ $item->email ?? '-' }}
                            </small>

                            <div class="text-muted small mt-1">
                                {{ $item->kab_kota ?? '-' }}
                            </div>

                        </td>

                        {{-- STATUS LAMARAN --}}
                        <td class="text-center">

                            @php
                            $statusLamaran = strtolower($lamaran->status_lamaran ?? 'pending');

                            $badgeClass = match($statusLamaran) {
                            'diterima' => 'success',
                            'ditolak' => 'danger',
                            'diproses' => 'warning',
                            default => 'secondary',
                            };
                            @endphp

                            <span class="badge badge-{{ $badgeClass }} px-3 py-2">
                                {{ strtoupper($lamaran->status_lamaran ?? 'PENDING') }}
                            </span>

                            @if($lamaran->catatan_perusahaan)
                            <div class="small text-muted mt-1">
                                {{ $lamaran->catatan_perusahaan }}
                            </div>
                            @endif

                        </td>

                        {{-- STATUS AKUN --}}
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

                    @endforeach

                    @empty

                    <tr>
                        <td colspan="7"
                            class="text-center py-5 text-muted">

                            <i class="fas fa-users fa-2x mb-2"></i>

                            <div>
                                Belum ada pelamar pada perusahaan ini
                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>