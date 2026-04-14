<div class="col-12 mb-3">
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Data Pencari Kerja</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-2">
                    <label>Nama Lengkap</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>NIK</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->nik ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>No KK</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->nomor_kk ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>Tempat, Tanggal Lahir</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->tempat_lahir ?? '-' }},
                        {{
                            !empty(optional($profil)->tanggal_lahir)
                                ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d-m-Y')
                                : '-'
                        }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>Jenis Kelamin</label>
                    <div class="form-control bg-light">
                        @php $jk = optional($profil)->jenis_kelamin; @endphp
                        {{ $jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : '-') }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>No HP</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->nomor_hp ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label>Email</label>
                    <div class="form-control bg-light">
                        {{ optional($profil)->email ?? '-' }}
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <label>Alamat</label>
                    <div class="form-control bg-light" style="min-height:70px;">
                        {{ optional($profil)->alamat ?? '-' }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>