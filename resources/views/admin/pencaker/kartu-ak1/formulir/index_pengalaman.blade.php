<div class="col-12 col-lg-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold text-dark">
                Pengalaman Kerja
            </h5>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            @php
            $profil = \App\Models\ProfilPencariKerja::where(
            'id_pengguna',
            Auth::user()->id_pengguna
            )->first();

            $kartuAk1 = null;
            $pengalaman = collect();

            if ($profil) {
            $kartuAk1 = \App\Models\KartuAk1::where(
            'id_pencari_kerja',
            $profil->id_pencari_kerja
            )->first();

            if ($kartuAk1) {
            $pengalaman = \App\Models\PengalamanKerjaAk1::where(
            'id_kartu_ak1',
            $kartuAk1->id_kartu_ak1
            )->latest()->get();
            }
            }

            $total = 5; // misalnya maksimal 5 pengalaman
            $filled = $pengalaman->count();
            $percent = $total > 0 ? min(100, round(($filled / $total) * 100)) : 0;
            @endphp

            {{-- PROGRESS --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-muted">
                        Jumlah Pengalaman
                    </span>
                    <span class="fw-bold text-primary">
                        {{ $filled }} Data
                    </span>
                </div>

                <div class="progress rounded-pill" style="height:10px;">
                    <div class="progress-bar bg-primary rounded-pill"
                        role="progressbar"
                        style="width: {{ $percent }}%;">
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Perusahaan</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengalaman as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $item->nama_perusahaan }}
                                </div>
                                <small class="text-muted d-block">
                                    {{ $item->deskripsi ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <span class="badge bg-primary px-3 py-2">
                                    {{ $item->jabatan }}
                                </span>
                            </td>

                            <td>
                                <small class="text-muted">
                                    {{ $item->mulai_bekerja 
                                        ? \Carbon\Carbon::parse($item->mulai_bekerja)->translatedFormat('d M Y') 
                                        : '-' }}
                                    -
                                    {{ $item->selesai_bekerja 
                                        ? \Carbon\Carbon::parse($item->selesai_bekerja)->translatedFormat('d M Y') 
                                        : 'Sekarang' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada pengalaman kerja
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- TOMBOL BAWAH TENGAH --}}
            <div class="text-center mt-4">
                <a href="{{ route('ak1.formulir.pengalaman-kerja') }}"
                    class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"> </i>
                    Kelola Pengalaman Kerja
                </a>
            </div>

        </div>
    </div>
</div>