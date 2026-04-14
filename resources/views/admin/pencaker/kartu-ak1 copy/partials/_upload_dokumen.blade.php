<div class="col-12">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Upload Dokumen AK1</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Foto Pas</label>
                    <input type="file" name="foto_pas" class="form-control">

                    @if($isEdit && !empty($ak1->foto_pas))
                    <small class="d-block mt-1">
                        <a href="{{ asset('storage/'.$ak1->foto_pas) }}" target="_blank">
                            Lihat file
                        </a>
                    </small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label>Scan KTP</label>
                    <input type="file" name="scan_ktp" class="form-control">

                    @if($isEdit && !empty($ak1->scan_ktp))
                    <small class="d-block mt-1">
                        <a href="{{ asset('storage/'.$ak1->scan_ktp) }}" target="_blank">
                            Lihat file
                        </a>
                    </small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label>Scan Ijazah</label>
                    <input type="file" name="scan_ijazah" class="form-control">

                    @if($isEdit && !empty($ak1->scan_ijazah))
                    <small class="d-block mt-1">
                        <a href="{{ asset('storage/'.$ak1->scan_ijazah) }}" target="_blank">
                            Lihat file
                        </a>
                    </small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label>Scan KK</label>
                    <input type="file" name="scan_kk" class="form-control">

                    @if($isEdit && !empty($ak1->scan_kk))
                    <small class="d-block mt-1">
                        <a href="{{ asset('storage/'.$ak1->scan_kk) }}" target="_blank">
                            Lihat file
                        </a>
                    </small>
                    @endif
                </div>

            </div>

            <div class="text-right mt-3">
                <button type="button" class="btn btn-submit btn-success" data-action="save_dokumen">
                    <i class="fas fa-upload"></i> Simpan Dokumen
                </button>
            </div>
        </div>
    </div>
</div>