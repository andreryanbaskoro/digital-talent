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

            const map = {
                draft: "draft",
                pending: "pending",
                disetujui: "disetujui",
                ditolak: "ditolak",
            };

            if (map[filter]) {
                return status === map[filter];
            }

            return true;
        });

        table.draw();
    }

    function setActiveTab(filter) {
        $(".filter-tab").removeClass("active");
        $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
    }

    $(document).on("click", ".filter-tab", function (e) {
        e.preventDefault();
        let filter = $(this).data("filter");
        setActiveTab(filter);
        applyFilter(filter);
    });

    setActiveTab("pending");
    applyFilter("pending");

    // =====================================================
    // ================= DETAIL MODAL =======================
    // =====================================================
    $(document).on("click", ".btn-detail", function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Memuat data...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        resetDetailModal();

        $.ajax({
            url: "/admin/disnaker/ak1/" + id + "/json",
            type: "GET",

            success: function (res) {
                Swal.close();

                $("#md_nama").text(res.nama);
                $("#md_nik").text("NIK: " + res.nik);
                $("#md_no").text(res.no);
                $("#md_tanggal").text(res.tanggal ?? "-");

                $("#md_foto").attr(
                    "src",
                    res.foto_url ??
                        "https://via.placeholder.com/120x120?text=No+Image",
                );

                // STATUS
                let badgeStatus = getStatusBadge(res.status);
                $("#md_status").html(badgeStatus);

                // PROFIL
                let badgeProfil = res.profil_lengkap
                    ? '<span class="badge badge-success">Lengkap</span>'
                    : '<span class="badge badge-danger">Belum Lengkap</span>';

                $("#md_profil").html(badgeProfil);

                // DOKUMEN
                $("#md_dokumen").html(renderDokumen(res));

                $("#modalDetailAk1").modal("show");
            },

            error: function () {
                Swal.close();
                Swal.fire("Error", "Gagal mengambil data", "error");
            },
        });
    });

    // =====================================================
    // ================= STATUS MODAL ======================
    // =====================================================
    $(document).on("click", ".btn-status", function () {
        let id = $(this).data("id");

        $("#formUpdateStatus").attr(
            "action",
            "/admin/disnaker/ak1/" + id + "/status",
        );

        Swal.fire({
            title: "Memuat data...",
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false,
        });

        resetStatusModal();

        $.ajax({
            url: "/admin/disnaker/ak1/" + id + "/json",
            type: "GET",

            success: function (res) {
                Swal.close();

                $("#verif_status").val(res.status);
                $("#md_nama_petugas").text(res.nama_petugas ?? "-");
                $("#md_nip_petugas").text(res.nip_petugas ?? "-");

                renderMasaBerlaku(res);

                toggleApprovalFields(res.status);

                $("#modalStatusAk1").modal("show");
            },

            error: function () {
                Swal.fire("Error", "Gagal mengambil data", "error");
            },
        });
    });

    // ================= STATUS CHANGE =================
    $(document).on("change", "#verif_status", function () {
        let status = $(this).val();

        toggleApprovalFields(status);

        if (status === "disetujui") {
            let masa = generateMasaBerlaku();

            $("#md_berlaku_mulai").text(formatDate(masa.start));
            $("#md_berlaku_sampai").text(formatDate(masa.end));
        } else {
            $("#md_berlaku_mulai").text("-");
            $("#md_berlaku_sampai").text("-");
        }
    });

    // ================= FUNCTIONS =================

    function renderMasaBerlaku(res) {
        if (res.status === "disetujui") {
            $("#md_berlaku_mulai").text(res.berlaku_mulai || "-");
            $("#md_berlaku_sampai").text(res.berlaku_sampai || "-");
        } else {
            $("#md_berlaku_mulai").text("-");
            $("#md_berlaku_sampai").text("-");
        }
    }

    function generateMasaBerlaku() {
        let today = new Date();

        let start = today.toISOString().split("T")[0];

        let endDate = new Date();
        endDate.setFullYear(endDate.getFullYear() + 2);
        let end = endDate.toISOString().split("T")[0];

        return { start, end };
    }

    function formatDate(date) {
        return new Date(date).toLocaleDateString("id-ID");
    }

    function toggleApprovalFields(status) {
        if (status === "disetujui") {
            $("#approvalFields").show();
        } else {
            $("#approvalFields").hide();
        }
    }

    function getStatusBadge(status) {
        switch (status) {
            case "draft":
                return '<span class="badge badge-secondary">Draft</span>';
            case "pending":
                return '<span class="badge badge-warning text-white">Pending</span>';
            case "disetujui":
                return '<span class="badge badge-success">Disetujui</span>';
            case "ditolak":
                return '<span class="badge badge-danger">Ditolak</span>';
            default:
                return '<span class="badge badge-light">Unknown</span>';
        }
    }

    function renderDokumen(res) {
        return `
            ${res.foto_pas_url ? `<a href="${res.foto_pas_url}" target="_blank">📷 Foto Pas</a><br>` : "❌ Foto Pas<br>"}
            ${res.ktp_url ? `<a href="${res.ktp_url}" target="_blank">🪪 KTP</a><br>` : "❌ KTP<br>"}
            ${res.ijazah_url ? `<a href="${res.ijazah_url}" target="_blank">🎓 Ijazah</a><br>` : "❌ Ijazah<br>"}
            ${res.kk_url ? `<a href="${res.kk_url}" target="_blank">👨‍👩‍👧 KK</a><br>` : "❌ KK<br>"}
        `;
    }

    function resetDetailModal() {
        $("#md_nama").text("Loading...");
        $("#md_nik").text("-");
        $("#md_no").text("-");
        $("#md_tanggal").text("-");
        $("#md_status").html("");
        $("#md_profil").html("-");
        $("#md_dokumen").html("-");
    }

    function resetStatusModal() {
        $("#md_berlaku_mulai").text("-");
        $("#md_berlaku_sampai").text("-");
    }

    $(document).on("change", "#verif_status", function () {
        let status = $(this).val();

        if (status === "disetujui" || status === "ditolak") {
            $("#catatan_petugas").prop("required", true);
        } else {
            $("#catatan_petugas").prop("required", false);
        }
    });
});