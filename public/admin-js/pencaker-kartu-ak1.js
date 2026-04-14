let keterampilanIndex = 0;

function addKeterampilan() {
    let wrapper = $("#keterampilan-wrapper");

    let html = `
        <div class="card mb-2 border keterampilan-item">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-2">
                        <label>Nama Keterampilan</label>
                        <input type="text"
                            name="keterampilan[${keterampilanIndex}][nama_keterampilan]"
                            class="form-control"
                            placeholder="Contoh: Laravel">
                    </div>

                    <div class="col-md-3 mb-2">
                        <label>Tingkat</label>
                        <input type="text"
                            name="keterampilan[${keterampilanIndex}][tingkat]"
                            class="form-control"
                            placeholder="Dasar / Mahir">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Sertifikat</label>
                        <input type="file"
                            name="keterampilan[${keterampilanIndex}][sertifikat]"
                            class="form-control">
                    </div>

                    <div class="col-md-1 d-flex align-items-end mb-2">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeKeterampilan(this)">
                            X
                        </button>
                    </div>

                </div>
            </div>
        </div>
    `;

    wrapper.append(html);
    keterampilanIndex++;
}

function removeKeterampilan(btn) {
    $(btn).closest(".keterampilan-item").remove();
}
