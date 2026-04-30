document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       EDIT DATA (SWEETALERT MODAL)
    ====================================================== */

    document.querySelectorAll(".btn-edit").forEach((button) => {
        button.addEventListener("click", function () {
            let id = this.dataset.id;
            let nama = this.dataset.nama || "";
            let jabatan = this.dataset.jabatan || "";
            let mulai = this.dataset.mulai || "";
            let selesai = this.dataset.selesai || "";
            let deskripsi = this.dataset.deskripsi || "";

            Swal.fire({
                title: "Edit Pengalaman Kerja",
                width: 650,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save me-1"></i> Update',
                cancelButtonText: "Batal",
                confirmButtonColor: "#0d6efd",
                cancelButtonColor: "#6c757d",
                focusConfirm: false,

                html: `
        <div class="container-fluid text-start mt-2">

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Perusahaan
                </label>
                <input type="text" 
                       id="swal_nama"
                       class="form-control"
                       placeholder="Contoh: PT Maju Jaya"
                       value="${nama}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Jabatan
                </label>
                <input type="text"
                       id="swal_jabatan"
                       class="form-control"
                       placeholder="Contoh: Supervisor Produksi"
                       value="${jabatan}">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Mulai Bekerja
                    </label>
                    <input type="date"
                           id="swal_mulai"
                           class="form-control"
                           value="${mulai}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Selesai Bekerja
                    </label>
                    <input type="date"
                           id="swal_selesai"
                           class="form-control"
                           value="${selesai}">
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label fw-semibold">
                    Deskripsi Pekerjaan
                </label>
                <textarea id="swal_deskripsi"
                          class="form-control"
                          rows="3"
                          placeholder="Jelaskan tanggung jawab atau pencapaian Anda...">${deskripsi}</textarea>
            </div>

        </div>
    `,

                preConfirm: () => {
                    let namaVal = document
                        .getElementById("swal_nama")
                        .value.trim();
                    let jabatanVal = document
                        .getElementById("swal_jabatan")
                        .value.trim();
                    let mulaiVal = document.getElementById("swal_mulai").value;

                    if (!namaVal || !jabatanVal || !mulaiVal) {
                        Swal.showValidationMessage(
                            "Nama Perusahaan, Jabatan, dan Tanggal Mulai wajib diisi!",
                        );
                        return false;
                    }

                    return {
                        nama_perusahaan: namaVal,
                        jabatan: jabatanVal,
                        mulai_bekerja: mulaiVal,
                        selesai_bekerja:
                            document.getElementById("swal_selesai").value,
                        deskripsi:
                            document.getElementById("swal_deskripsi").value,
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Menyimpan...",
                        text: "Mohon tunggu sebentar",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = `/pencaker/ak1/pengalaman/${id}`;

                    let csrf = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrf}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="nama_perusahaan" value="${result.value.nama_perusahaan}">
                        <input type="hidden" name="jabatan" value="${result.value.jabatan}">
                        <input type="hidden" name="mulai_bekerja" value="${result.value.mulai_bekerja}">
                        <input type="hidden" name="selesai_bekerja" value="${result.value.selesai_bekerja}">
                        <input type="hidden" name="deskripsi" value="${result.value.deskripsi}">
                    `;

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    /* =====================================================
       DELETE CONFIRM
    ====================================================== */
});
