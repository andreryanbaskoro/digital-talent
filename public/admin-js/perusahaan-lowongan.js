let table;

$(document).ready(function () {
    // ================= DATATABLE =================
    table = $("#table-1").DataTable({
        paging: true,
        lengthChange: true,
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
                    cell.innerHTML = i + 1;
                });
        })
        .draw();

    // ================= FILTER =================
    function applyFilter(filter) {
        $.fn.dataTable.ext.search = [];

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            let row = table.row(dataIndex).node();
            let status = $(row).data("status");
            let deleted = $(row).data("deleted");

            if (filter === "all") return true;

            if (filter === "pending")
                return status === "pending" && deleted == 0;

            if (filter === "disetujui")
                return status === "disetujui" && deleted == 0;

            if (filter === "ditolak")
                return status === "ditolak" && deleted == 0;

            if (filter === "deleted") return deleted == 1;

            return true;
        });

        table.draw();
    }

    // ================= TAB ACTIVE =================
    function setActiveTab(filter) {
        $(".filter-tab").removeClass("active");
        $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
    }

    // ================= CLICK TAB =================
    $(".filter-tab").on("click", function (e) {
        e.preventDefault();

        let filter = $(this).data("filter");

        setActiveTab(filter);
        applyFilter(filter);
    });

    // ================= DEFAULT =================
    setActiveTab("all");
    applyFilter("all");
});

// ================= HELPER =================
function escapeHtml(text) {
    return $("<div>")
        .text(text ?? "-")
        .html();
}

// ================= SHOW DETAIL MODAL =================
$(document).on("click", ".btn-show", function () {
    let url = $(this).data("url");

    $.get(url, function (data) {
        // Format Gaji
        let gaji = "-";
        if (data.gaji_minimum && data.gaji_maksimum) {
            gaji =
                "Rp" +
                parseInt(data.gaji_minimum).toLocaleString("id-ID") +
                " - Rp" +
                parseInt(data.gaji_maksimum).toLocaleString("id-ID");
        }

        // Status Badge
        let badgeClass = "secondary";
        let statusText = data.status ?? "-";

        if (data.deleted_at) {
            badgeClass = "dark";
            statusText = "Terhapus";
        } else if (data.status === "pending") {
            badgeClass = "warning";
        } else if (data.status === "disetujui") {
            badgeClass = "success";
        } else if (data.status === "ditolak") {
            badgeClass = "danger";
        }

        Swal.fire({
            title: escapeHtml(data.judul_lowongan),
            width: 850,
            confirmButtonText: "Tutup",
            confirmButtonColor: "#3085d6",
            html: `
                <div class="text-left">

                    <table class="table table-bordered table-sm mb-0">
                        <tr>
                            <th width="35%">ID</th>
                            <td>${escapeHtml(data.id)}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>${escapeHtml(data.lokasi)}</td>
                        </tr>
                        <tr>
                            <th>Jenis Pekerjaan</th>
                            <td>${escapeHtml(data.jenis_pekerjaan)}</td>
                        </tr>
                        <tr>
                            <th>Sistem Kerja</th>
                            <td>${escapeHtml(data.sistem_kerja)}</td>
                        </tr>
                        <tr>
                            <th>Gaji</th>
                            <td>${escapeHtml(gaji)}</td>
                        </tr>
                        <tr>
                            <th>Pendidikan Minimum</th>
                            <td>${escapeHtml(data.pendidikan_minimum)}</td>
                        </tr>
                        <tr>
                            <th>Pengalaman Minimum</th>
                            <td>${escapeHtml(data.pengalaman_minimum)}</td>
                        </tr>
                        <tr>
                            <th>Kuota</th>
                            <td>${escapeHtml(data.kuota)}</td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>
                                ${escapeHtml(data.tanggal_mulai)} s/d 
                                ${escapeHtml(data.tanggal_berakhir)}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-${badgeClass}">
                                    ${escapeHtml(statusText)}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <strong>Deskripsi</strong>
                        <div class="border rounded p-2 mt-1" style="max-height:180px; overflow:auto;">
                            ${escapeHtml(data.deskripsi).replace(/\n/g, "<br>")}
                        </div>
                    </div>

                </div>
            `,
        });
    }).fail(function () {
        Swal.fire("Error", "Gagal mengambil data.", "error");
    });
});
