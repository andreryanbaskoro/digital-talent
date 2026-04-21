let table;

$(document).ready(function () {
    // ================= DATATABLE =================
    table = $("#table-1").DataTable({
        paging: true,
        pageLength: 10,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
    });

    // ================= AUTO NUMBER =================
    table
        .on("order.dt search.dt", function () {
            table
                .column(0, { search: "applied", order: "applied" })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = `<span class="badge badge-light">${i + 1}</span>`;
                });
        })
        .draw();

    // ================= FILTER =================
    function applyFilter(filter) {
        $.fn.dataTable.ext.search = [];

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            let row = table.row(dataIndex).node();
            let deleted = $(row).data("deleted");
            let status = $(row).data("status");

            if (filter === "all") return true;
            if (filter === "deleted") return deleted == 1;

            return status === filter;
        });

        table.draw();
    }

    // ================= TAB =================
    $(".filter-tab").on("click", function (e) {
        e.preventDefault();
        $(".filter-tab").removeClass("active");
        $(this).addClass("active");

        let filter = $(this).data("filter");
        applyFilter(filter);
    });

    applyFilter("all");
});

// ================= HELPER =================
function esc(text) {
    return $("<div>")
        .text(text ?? "-")
        .html();
}

function rupiah(angka) {
    if (angka === null || angka === undefined || angka === "") return "-";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(angka);
}

function setText(id, value) {
    $(id).text(value ?? "-");
}

function statusMeta(status, deletedAt) {
    const uiLabel = {
        dikirim: "Lamaran Masuk",
        diproses: "Dalam Review",
        diterima: "Lolos Seleksi",
        ditolak: "Tidak Lolos Seleksi",
    };

    const badge = {
        dikirim: "primary",
        diproses: "warning",
        diterima: "success",
        ditolak: "danger",
    };

    if (deletedAt) {
        return {
            text: "Terhapus",
            cls: "dark",
        };
    }

    return {
        text: uiLabel[status] ?? status ?? "-",
        cls: badge[status] ?? "secondary",
    };
}

// ================= SHOW DETAIL MODAL =================
$(document).on("click", ".btn-show", function () {
    let url = $(this).data("url");

    $.get(url, function (data) {
        const meta = statusMeta(data.status_lamaran, data.deleted_at);

        // ================= HEADER / FOTO =================
        if (data.pencari_kerja?.foto) {
            $("#swal-foto").html(`
                <img src="${data.pencari_kerja.foto}"
                     class="rounded-circle border"
                     style="width:88px;height:88px;object-fit:cover;"
                     alt="Foto Pelamar">
            `);
        } else {
            $("#swal-foto").html(`
                <div class="rounded-circle border d-flex align-items-center justify-content-center bg-light"
                     style="width:88px;height:88px;">
                    <i class="fas fa-user text-muted fa-2x"></i>
                </div>
            `);
        }

        $("#swal-nama").text(data.pencari_kerja?.nama_lengkap ?? "-");
        $("#swal-id").text(data.id_lamaran ?? "-");
        $("#swal-status")
            .text(meta.text)
            .attr("class", `badge badge-${meta.cls} px-3 py-2`);

        // ================= DATA PELAMAR =================
        setText("#swal-nik", data.pencari_kerja?.nik);
        setText("#swal-email", data.pencari_kerja?.email);
        setText("#swal-hp", data.pencari_kerja?.nomor_hp);
        setText("#swal-alamat", data.pencari_kerja?.alamat);
        setText("#swal-tanggal-lahir", data.pencari_kerja?.tanggal_lahir);
        setText("#swal-tempat-lahir", data.pencari_kerja?.tempat_lahir);
        setText("#swal-jenis-kelamin", data.pencari_kerja?.jenis_kelamin);
        setText("#swal-agama", data.pencari_kerja?.agama);
        setText("#swal-status-kawin", data.pencari_kerja?.status_perkawinan);

        // ================= DATA LAMARAN / LOWONGAN =================
        setText("#swal-tanggal", data.tanggal_lamar);
        setText("#swal-lowongan", data.lowongan?.judul_lowongan);
        setText("#swal-lokasi", data.lowongan?.lokasi);
        setText("#swal-jenis", data.lowongan?.jenis_pekerjaan);
        setText("#swal-sistem", data.lowongan?.sistem_kerja);
        setText("#swal-kuota", data.lowongan?.kuota);
        setText(
            "#swal-gaji",
            `${rupiah(data.lowongan?.gaji_minimum)} - ${rupiah(data.lowongan?.gaji_maksimum)}`,
        );

        // ================= SKILL =================
        let skillHtml = "";

        (data.sub_kriteria ?? []).forEach((s) => {
            let nilai = Number(s.nilai ?? 0);
            let stars = "";

            for (let i = 1; i <= 5; i++) {
                stars +=
                    i <= nilai
                        ? `<i class="fas fa-star text-warning"></i>`
                        : `<i class="far fa-star text-muted"></i>`;
            }

            skillHtml += `
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="font-weight-bold">
                            <i class="fas fa-check-circle text-primary mr-1"></i>
                            ${esc(s.nama)}
                        </div>
                        <small class="text-muted">${nilai}/5</small>
                    </div>
                    <div class="mt-1 ml-4">${stars}</div>
                </div>
            `;
        });

        $("#swal-skill").html(
            skillHtml ||
                "<small class='text-muted'>Tidak ada data skill</small>",
        );

        // ================= DOKUMEN =================
        let dok = "";

        (data.dokumen ?? []).forEach((d) => {
            if (!d.url) return;

            dok += `
                <a href="${d.url}" target="_blank" class="d-block mb-2 text-decoration-none">
                    <i class="fas fa-file-alt text-primary mr-1"></i>
                    ${esc(d.jenis)}
                </a>
            `;
        });

        $("#swal-dokumen").html(
            dok || "<small class='text-muted'>Tidak ada dokumen</small>",
        );

        // ================= CATATAN =================
        setText("#swal-catatan", data.catatan_perusahaan);

        // ================= SWAL =================
        Swal.fire({
            width: "900px",
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Tutup',
            confirmButtonColor: "#3085d6",
            title: '<span class="h5 mb-0"><i class="fas fa-briefcase text-primary mr-2"></i>Detail Lamaran</span>',
            html: $("#swal-lamaran-template").html(),
            didOpen: function () {
                // supaya tampil lebih enak tanpa CSS tambahan
                $(".swal2-html-container").css("text-align", "left");
            },
        });
    }).fail(function () {
        Swal.fire("Error", "Gagal mengambil data", "error");
    });
});
