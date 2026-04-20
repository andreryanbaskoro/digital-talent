<div class="col-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold text-dark">
                Dokumen Pribadi
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

            if ($profil) {
            $kartuAk1 = \App\Models\KartuAk1::where(
            'id_pencari_kerja',
            $profil->id_pencari_kerja
            )->first();
            }

            $dokumen = [
            'Foto Pas' => $kartuAk1->foto_pas ?? null,
            'Scan KTP' => $kartuAk1->scan_ktp ?? null,
            'Scan Ijazah' => $kartuAk1->scan_ijazah ?? null,
            'Scan KK' => $kartuAk1->scan_kk ?? null,
            ];

            $filled = collect($dokumen)->filter()->count();
            $total = count($dokumen);
            $percent = $total > 0 ? round(($filled / $total) * 100) : 0;
            @endphp

            {{-- PROGRESS --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-muted">
                        Kelengkapan Dokumen
                    </span>
                    <span class="fw-bold text-primary">
                        {{ $percent }}%
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
                            <th style="width: 60%">Dokumen</th>
                            <th style="width: 40%" class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($dokumen as $label => $value)
                        <tr>
                            <td class="fw-medium">
                                {{ $label }}
                            </td>

                            <td class="text-center">
                                @if($value)
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Lengkap
                                </span>
                                @else
                                <span class="badge bg-secondary px-3 py-2">
                                    Belum Lengkap
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- TOMBOL DI BAWAH TENGAH --}}
            <div class="text-center mt-4">
                <a href="{{ route('pencaker.ak1.dokumen-pribadi') }}"
                    class="btn btn-primary px-4">
                    <i class="fas fa-upload mr-1"></i>
                    Unggah Dokumen
                </a>
            </div>

        </div>
    </div>
</div>