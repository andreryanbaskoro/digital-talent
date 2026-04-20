<div class="col-12 col-lg-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold text-dark">
                Keterampilan
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
            $keterampilan = collect();

            if ($profil) {
            $kartuAk1 = \App\Models\KartuAk1::where(
            'id_pencari_kerja',
            $profil->id_pencari_kerja
            )->first();

            if ($kartuAk1) {
            $keterampilan = \App\Models\KeterampilanAk1::where(
            'id_kartu_ak1',
            $kartuAk1->id_kartu_ak1
            )->latest()
            ->get();
            }
            }

            $totalTarget = 5; // misal idealnya minimal 5 skill
            $filled = $keterampilan->count();
            $percent = $totalTarget > 0
            ? min(100, round(($filled / $totalTarget) * 100))
            : 0;
            @endphp

            {{-- PROGRESS --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-muted">
                        Jumlah Keterampilan
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
                <table class="table align-middle table-hover">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Nama</th>
                            <th style="width: 30%">Tingkat</th>
                            <th style="width: 30%" class="text-center">Sertifikat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($keterampilan as $item)
                        <tr>
                            <td>
                                <div class="fw-medium">
                                    {{ $item->nama_keterampilan }}
                                </div>
                            </td>

                            <td>
                                @if($item->tingkat == 'Pemula')
                                <span class="badge bg-secondary px-3 py-2">Pemula</span>
                                @elseif($item->tingkat == 'Menengah')
                                <span class="badge bg-primary px-3 py-2">Menengah</span>
                                @else
                                <span class="badge bg-success px-3 py-2">Mahir</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($item->sertifikat)
                                <a href="{{ Storage::url($item->sertifikat) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    Lihat
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada keterampilan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- TOMBOL BAWAH TENGAH --}}
            <div class="text-center mt-4">
                <a href="{{ route('pencaker.ak1.keterampilan.index') }}"
                    class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>
                    Kelola Keterampilan
                </a>
            </div>

        </div>
    </div>
</div>