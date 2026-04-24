let table;

$(document).ready(function () {
    table = $("#table-1").DataTable({
        paging: true,
        pageLength: 10,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
    });

    // AUTO NUMBER
    table.on("draw.dt", function () {
        let PageInfo = table.page.info();
        table
            .column(1, { page: "current" })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = `<span class="badge badge-light">${i + 1 + PageInfo.start}</span>`;
            });
    });

    // CHECK ALL
    $("#checkAll").on("change", function () {
        $(".row-check").prop("checked", this.checked);
    });

    function getSelected() {
        return $(".row-check:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
    }

    // ================= HELPER =================

    function confirmAction({
        title,
        text,
        icon,
        confirmText,
        confirmColor,
        onConfirm,
    }) {
        Swal.fire({
            title,
            text,
            icon,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: "Batal",
            confirmButtonColor: confirmColor,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Memproses...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });
                onConfirm();
            }
        });
    }

    function ajaxAction(url, method = "POST", data = {}, callback = null) {
        $.ajax({
            url: url,
            type: method,
            data: {
                _token: csrfToken,
                ...data,
            },
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res?.message || "Berhasil diproses",
                    timer: 1200,
                    showConfirmButton: false,
                }).then(() => {
                    if (callback) callback();
                    else location.reload();
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: xhr.responseJSON?.message || "Terjadi kesalahan",
                });
            },
        });
    }

    // ================= ACTION =================

    $("#btnMarkSelected").click(function () {
        let ids = getSelected();

        if (!ids.length) return Swal.fire("Info", "Pilih data dulu", "info");

        confirmAction({
            title: "Tandai dibaca?",
            text: `${ids.length} notifikasi`,
            icon: "question",
            confirmText: "Ya",
            confirmColor: "#3085d6",
            onConfirm: () => ajaxAction(routeMarkSelected, "POST", { ids }),
        });
    });

    $("#btnMarkAll").click(function () {
        confirmAction({
            title: "Tandai semua?",
            text: "Semua notifikasi",
            icon: "question",
            confirmText: "Ya",
            confirmColor: "#3085d6",
            onConfirm: () => ajaxAction(routeMarkAll),
        });
    });

    $("#btnDeleteSelected").click(function () {
        let ids = getSelected();

        if (!ids.length) return Swal.fire("Info", "Pilih data dulu", "info");

        confirmAction({
            title: "Hapus?",
            text: `${ids.length} notifikasi`,
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () => ajaxAction(routeDeleteSelected, "POST", { ids }),
        });
    });

    $("#btnDeleteAll").click(function () {
        confirmAction({
            title: "Hapus semua?",
            text: "Semua notifikasi",
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () => ajaxAction(routeDeleteAll, "DELETE"),
        });
    });

    $("#btnRestoreSelected").click(function () {
        let ids = getSelected();

        if (!ids.length) return Swal.fire("Info", "Pilih data dulu", "info");

        confirmAction({
            title: "Pulihkan?",
            text: `${ids.length} notifikasi`,
            icon: "question",
            confirmText: "Ya",
            confirmColor: "#28a745",
            onConfirm: () => ajaxAction(routeRestoreSelected, "POST", { ids }),
        });
    });

    $("#btnForceDeleteSelected").click(function () {
        let ids = getSelected();

        if (!ids.length) return Swal.fire("Info", "Pilih data dulu", "info");

        confirmAction({
            title: "Hapus permanen?",
            text: `${ids.length} notifikasi`,
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () =>
                ajaxAction(routeForceDeleteSelected, "DELETE", { ids }),
        });
    });

    $("#btnForceDeleteAll").click(function () {
        confirmAction({
            title: "Hapus semua permanen?",
            text: "Tidak bisa dikembalikan",
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () => ajaxAction(routeForceDeleteAll, "DELETE"),
        });
    });

    // ================= SINGLE ACTION =================

    $(document).on("click", ".btn-delete-single", function () {
        confirmAction({
            title: "Hapus?",
            text: "Masuk ke sampah",
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () => ajaxAction($(this).data("url"), "DELETE"),
        });
    });

    $(document).on("click", ".btn-restore-single", function () {
        confirmAction({
            title: "Pulihkan?",
            text: "Kembalikan data",
            icon: "question",
            confirmText: "Ya",
            confirmColor: "#28a745",
            onConfirm: () => ajaxAction($(this).data("url")),
        });
    });

    $(document).on("click", ".btn-force-delete-single", function () {
        confirmAction({
            title: "Hapus permanen?",
            text: "Tidak bisa dikembalikan",
            icon: "warning",
            confirmText: "Ya",
            confirmColor: "#d33",
            onConfirm: () => ajaxAction($(this).data("url"), "DELETE"),
        });
    });
});
