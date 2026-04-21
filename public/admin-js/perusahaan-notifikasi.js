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

    table
        .on("order.dt search.dt", function () {
            table
                .column(1, { search: "applied", order: "applied" })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = `<span class="badge badge-light">${i + 1}</span>`;
                });
        })
        .draw();

    $("#checkAll").on("change", function () {
        $(".row-check").prop("checked", $(this).prop("checked"));
    });

    function getSelected() {
        let ids = [];
        $(".row-check:checked").each(function () {
            ids.push($(this).val());
        });
        return ids;
    }

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

    function postAction(url, data, successCallback) {
        $.post(url, data)
            .done(function () {
                if (typeof successCallback === "function") successCallback();
                else location.reload();
            })
            .fail(function () {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan saat memproses data.",
                });
            });
    }

    function deleteAction(url, successCallback) {
        Swal.fire({
            title: "Memproses...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: csrfToken,
                _method: "DELETE",
            },
            success: function (response) {
                Swal.close();

                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text:
                        response?.message ||
                        "Data berhasil dipindahkan ke tempat sampah.",
                    timer: 1200,
                    showConfirmButton: false,
                }).then(() => {
                    if (typeof successCallback === "function") {
                        successCallback();
                    } else {
                        location.reload();
                    }
                });
            },
            error: function (xhr) {
                Swal.close();

                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text:
                        xhr.responseJSON?.message ||
                        "Terjadi kesalahan saat memproses data.",
                });
            },
        });
    }

    $("#btnMarkSelected").on("click", function () {
        let ids = getSelected();
        if (ids.length === 0) {
            return Swal.fire({
                icon: "info",
                title: "Belum ada pilihan",
                text: "Silakan pilih notifikasi terlebih dahulu",
            });
        }

        confirmAction({
            title: "Tandai sebagai dibaca?",
            text: `${ids.length} notifikasi akan ditandai`,
            icon: "question",
            confirmText: "Ya, Tandai",
            confirmColor: "#3085d6",
            onConfirm: () =>
                postAction(routeMarkSelected, { _token: csrfToken, ids }),
        });
    });

    $("#btnMarkAll").on("click", function () {
        confirmAction({
            title: "Tandai semua sebagai dibaca?",
            text: "Semua notifikasi akan ditandai",
            icon: "question",
            confirmText: "Ya, Tandai Semua",
            confirmColor: "#3085d6",
            onConfirm: () => postAction(routeMarkAll, { _token: csrfToken }),
        });
    });

    $("#btnDeleteSelected").on("click", function () {
        let ids = getSelected();

        if (ids.length === 0) {
            return Swal.fire({
                icon: "info",
                title: "Belum ada pilihan",
                text: "Silakan pilih notifikasi terlebih dahulu",
            });
        }

        confirmAction({
            title: "Hapus notifikasi terpilih?",
            text: `${ids.length} notifikasi akan dipindahkan ke tempat sampah`,
            icon: "warning",
            confirmText: "Ya, Hapus",
            confirmColor: "#d33",
            onConfirm: () =>
                postAction(routeDeleteSelected, { _token: csrfToken, ids }),
        });
    });
    $("#btnDeleteAll").on("click", function () {
        confirmAction({
            title: "Hapus semua notifikasi?",
            text: "Semua notifikasi akan dipindahkan ke tempat sampah",
            icon: "error",
            confirmText: "Ya, Hapus Semua",
            confirmColor: "#d33",
            onConfirm: () => deleteAction(routeDeleteAll),
        });
    });

    $("#btnRestoreSelected").on("click", function () {
        let ids = getSelected();
        if (ids.length === 0) {
            return Swal.fire({
                icon: "info",
                title: "Belum ada pilihan",
                text: "Silakan pilih notifikasi terlebih dahulu",
            });
        }

        confirmAction({
            title: "Pulihkan notifikasi terpilih?",
            text: `${ids.length} notifikasi akan dikembalikan`,
            icon: "question",
            confirmText: "Ya, Pulihkan",
            confirmColor: "#28a745",
            onConfirm: () =>
                postAction(routeRestoreSelected, { _token: csrfToken, ids }),
        });
    });

    $("#btnForceDeleteSelected").on("click", function () {
        let ids = getSelected();
        if (ids.length === 0) {
            return Swal.fire({
                icon: "info",
                title: "Belum ada pilihan",
                text: "Silakan pilih notifikasi terlebih dahulu",
            });
        }

        confirmAction({
            title: "Hapus permanen notifikasi terpilih?",
            text: `${ids.length} notifikasi akan dihapus permanen`,
            icon: "warning",
            confirmText: "Ya, Hapus Permanen",
            confirmColor: "#d33",
            onConfirm: () =>
                deleteAction(routeForceDeleteSelected, function () {
                    location.reload();
                }),
        });
    });

    $("#btnForceDeleteAll").on("click", function () {
        confirmAction({
            title: "Hapus permanen semua notifikasi?",
            text: "Semua notifikasi pada tab terhapus akan dihapus permanen",
            icon: "warning",
            confirmText: "Ya, Hapus Permanen",
            confirmColor: "#d33",
            onConfirm: () => deleteAction(routeForceDeleteAll),
        });
    });

    // tombol per baris
    $(document).on("click", ".btn-delete-single", function () {
        const url = $(this).data("url");

        confirmAction({
            title: "Hapus notifikasi?",
            text: "Notifikasi akan dipindahkan ke tempat sampah",
            icon: "warning",
            confirmText: "Ya, Hapus",
            confirmColor: "#d33",
            onConfirm: () => deleteAction(url),
        });
    });

    $(document).on("click", ".btn-restore-single", function () {
        const url = $(this).data("url");

        confirmAction({
            title: "Pulihkan notifikasi?",
            text: "Notifikasi akan dikembalikan dari tempat sampah",
            icon: "question",
            confirmText: "Ya, Pulihkan",
            confirmColor: "#28a745",
            onConfirm: () => postAction(url, { _token: csrfToken }),
        });
    });

    $(document).on("click", ".btn-force-delete-single", function () {
        const url = $(this).data("url");

        confirmAction({
            title: "Hapus permanen?",
            text: "Data akan hilang permanen dan tidak bisa dikembalikan",
            icon: "warning",
            confirmText: "Ya, Hapus Permanen",
            confirmColor: "#d33",
            onConfirm: () => deleteAction(url),
        });
    });
});
