document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       EDIT DATA (SWEETALERT MODAL)
    ====================================================== */

    document.querySelectorAll(".btn-edit").forEach((button) => {
        button.addEventListener("click", function () {
            let id = this.dataset.id;
            let jenjang = this.dataset.jenjang || "";
            let nama = this.dataset.nama || "";
            let jurusan = this.dataset.jurusan || "";
            let masuk = this.dataset.masuk || "";
            let lulus = this.dataset.lulus || "";
            let nilai = this.dataset.nilai || "";

            Swal.fire({
                title: "Edit Riwayat Pendidikan",
                width: 650,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save me-1"></i> Update',
                cancelButtonText: "Batal",
                confirmButtonColor: "#0d6efd",
                cancelButtonColor: "#6c757d",

                html: `
                    <div class="text-start mt-2">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenjang</label>
                            <select id="swal_jenjang" class="form-control">
                                ${[
                                    "SD",
                                    "SMP",
                                    "SMA/SMK",
                                    "D1",
                                    "D2",
                                    "D3",
                                    "D4",
                                    "S1",
                                    "S2",
                                    "S3",
                                ]
                                    .map(
                                        (j) =>
                                            `<option value="${j}" ${j === jenjang ? "selected" : ""}>${j}</option>`,
                                    )
                                    .join("")}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Sekolah</label>
                            <input type="text" id="swal_nama"
                                class="form-control"
                                value="${nama}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jurusan</label>
                            <input type="text" id="swal_jurusan"
                                class="form-control"
                                value="${jurusan}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tahun Masuk</label>
                                <input type="number" id="swal_masuk"
                                    class="form-control"
                                    value="${masuk}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tahun Lulus</label>
                                <input type="number" id="swal_lulus"
                                    class="form-control"
                                    value="${lulus}">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Nilai Akhir / IPK</label>
                            <input type="number" step="0.01"
                                id="swal_nilai"
                                class="form-control"
                                value="${nilai}">
                        </div>

                    </div>
                `,

                preConfirm: () => {
                    let namaVal = document
                        .getElementById("swal_nama")
                        .value.trim();
                    let masukVal = document.getElementById("swal_masuk").value;

                    if (!namaVal || !masukVal) {
                        Swal.showValidationMessage(
                            "Nama Sekolah dan Tahun Masuk wajib diisi!",
                        );
                        return false;
                    }

                    return {
                        jenjang: document.getElementById("swal_jenjang").value,
                        nama_sekolah: namaVal,
                        jurusan: document.getElementById("swal_jurusan").value,
                        tahun_masuk: masukVal,
                        tahun_lulus:
                            document.getElementById("swal_lulus").value,
                        nilai_akhir:
                            document.getElementById("swal_nilai").value,
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Menyimpan...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                    });

                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = `/pencaker/ak1/pendidikan/${id}`;

                    let csrf = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrf}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="jenjang" value="${result.value.jenjang}">
                        <input type="hidden" name="nama_sekolah" value="${result.value.nama_sekolah}">
                        <input type="hidden" name="jurusan" value="${result.value.jurusan}">
                        <input type="hidden" name="tahun_masuk" value="${result.value.tahun_masuk}">
                        <input type="hidden" name="tahun_lulus" value="${result.value.tahun_lulus}">
                        <input type="hidden" name="nilai_akhir" value="${result.value.nilai_akhir}">
                    `;

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
