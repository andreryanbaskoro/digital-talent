<div class="col-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold text-dark">
                Data Pribadi
            </h5>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            @php
            $profil = \App\Models\ProfilPencariKerja::where(
            'id_pengguna',
            Auth::user()->id_pengguna
            )->first();

            $fields = [
            'NIK' => $profil->nik ?? null,
            'Nomor KK' => $profil->nomor_kk ?? null,
            'Nama Lengkap' => $profil->nama_lengkap ?? null,
            'Tempat Lahir' => $profil->tempat_lahir ?? null,
            'Tanggal Lahir' => $profil->tanggal_lahir ?? null,
            'Jenis Kelamin' => $profil->jenis_kelamin ?? null,
            'Agama' => $profil->agama ?? null,
            'Status Perkawinan' => $profil->status_perkawinan ?? null,
            'Alamat' => $profil->alamat ?? null,
            'RT/RW' => ($profil && $profil->rt && $profil->rw) ? true : null,
            'Kelurahan' => $profil->kelurahan ?? null,
            'Kecamatan' => $profil->kecamatan ?? null,
            'Kabupaten' => $profil->kabupaten ?? null,
            'Provinsi' => $profil->provinsi ?? null,
            'Kode Pos' => $profil->kode_pos ?? null,
            'Nomor HP' => $profil->nomor_hp ?? null,
            'Email' => $profil->email ?? null,
            'Foto' => $profil->foto ?? null,
            ];

            $filled = collect($fields)->filter()->count();
            $total = count($fields);
            $percent = $total > 0 ? round(($filled / $total) * 100) : 0;
            @endphp

            {{-- PROGRESS --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-muted">
                        Kelengkapan Profil
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
                <table class="table align-middle table-hover">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 60%">Field</th>
                            <th style="width: 40%" class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($fields as $label => $value)
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
                <a href="{{ route('pencaker.profil.edit') }}"
                    class="btn btn-primary px-4">
                    <i class="fas fa-pen mr-1"></i>
                    Edit Data
                </a>
            </div>

        </div>
    </div>
</div>