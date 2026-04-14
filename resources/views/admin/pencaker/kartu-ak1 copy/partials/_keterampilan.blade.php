<form method="POST" action="{{ route('ak1.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Keterampilan Fields -->
    <div class="col-12">
        <div class="card card-outline card-success shadow-sm mb-3">
            <!-- Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">Keterampilan</h3>
                <button type="button" class="btn btn-sm btn-success" onclick="addKeterampilan()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <!-- Body -->
            <div class="card-body">
                <div id="keterampilan-wrapper">
                    @foreach($keterampilanItems as $index => $item)
                    <div class="card mb-2 border keterampilan-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label>Nama Keterampilan</label>
                                    <input type="text"
                                        name="keterampilan[{{ $index }}][nama_keterampilan]"
                                        class="form-control"
                                        value="{{ old('keterampilan.' . $index . '.nama_keterampilan', $item['nama_keterampilan'] ?? '') }}"
                                        placeholder="Contoh: Laravel">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Tingkat</label>
                                    <input type="text"
                                        name="keterampilan[{{ $index }}][tingkat]"
                                        class="form-control"
                                        value="{{ old('keterampilan.' . $index . '.tingkat', $item['tingkat'] ?? '') }}"
                                        placeholder="Dasar / Menengah / Mahir">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Sertifikat</label>
                                    <input type="file"
                                        name="keterampilan[{{ $index }}][sertifikat]"
                                        class="form-control">
                                    @if(!empty($item['sertifikat_path']))
                                    <small class="d-block mt-2">
                                        <a href="{{ asset('storage/'.$item['sertifikat_path']) }}" target="_blank">
                                            Lihat file
                                        </a>
                                    </small>
                                    @endif
                                </div>
                                <div class="col-md-1 d-flex align-items-end mb-2">
                                    <button type="button"
                                        class="btn btn-danger btn-sm w-100"
                                        onclick="removeKeterampilan(this)">
                                        X
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-success btn-submit">
                    <i class="fas fa-save"></i> Simpan Keterampilan
                </button>
            </div>

        </div>
    </div>
</form>