<div class="col-12 col-lg-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold text-dark">
                Riwayat Pendidikan
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
            $pendidikan = collect();

            if ($profil) {
            $kartuAk1 = \App\Models\KartuAk1::where(
            'id_pencari_kerja',
            $profil->id_pencari_kerja
            )->first();

            if ($kartuAk1) {
            $pendidikan = \App\Models\RiwayatPendidikanAk1::where(
            'id_kartu_ak1',
            $kartuAk1->id_kartu_ak1
            )->latest()->get();
            }
            }

            $total = 5; // misal maksimal 5 pendidikan
            $filled = $pendidikan->count();
            $percent = $total > 0 ? min(100, round(($filled / $total) * 100)) : 0;
            @endphp

            {{-- PROGRESS --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-muted">
                        Jumlah Pendidikan
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
                            <th>Jenjang</th>
                            <th>Sekolah / Universitas</th>
                            <th>Periode</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pendidikan as $item)
                        <tr>
                            <td>
                                <span class="badge bg-primary px-3 py-2">
                                    {{ $item->jenjang }}
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $item->jurusan ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->nama_sekolah }}
                                </div>
                                <small class="text-muted">
                                    Nilai: {{ $item->nilai_akhir ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <small class="text-muted">
                                    {{ $item->tahun_masuk ?? '-' }}
                                    -
                                    {{ $item->tahun_lulus ?? 'Sekarang' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada riwayat pendidikan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- TOMBOL BAWAH TENGAH --}}
            <div class="text-center mt-4">
                <a href="{{ route('pencaker.ak1.formulir.riwayat-pendidikan') }}"
                    class="btn btn-primary px-4">
                    <i class="fas fa-graduation-cap me-2"> </i>
                    Kelola Riwayat Pendidikan
                </a>
            </div>

        </div>
    </div>
</div>