// ================= GLOBAL FUNCTION =================
function showConfirm(options, callback) {
    Swal.fire({
        title: options.title || "Konfirmasi",
        text: options.text || "Apakah Anda yakin?",
        icon: options.icon || "question",
        showCancelButton: true,
        confirmButtonText: options.confirmText || "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) callback();
    });
}

function confirmWithNote(options) {
    let csrf = $('meta[name="csrf-token"]').attr("content");

    Swal.fire({
        title: options.title || "Konfirmasi",
        text: options.text || "",
        input: "textarea",
        inputPlaceholder: options.placeholder || "Tulis catatan...",
        icon: options.icon || "question",
        showCancelButton: true,
        confirmButtonText: options.confirmText || "Ya",
        preConfirm: (value) => {
            if (options.required && !value) {
                Swal.showValidationMessage("Catatan wajib diisi!");
            }
            return value;
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // 🔥 TAMPILKAN SPINNER
            showLoading(options.loadingText || "Memproses...");

            let form = $("<form>", {
                method: "POST",
                action: options.url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);

            if (options.method) {
                form.append(
                    `<input type="hidden" name="_method" value="${options.method}">`,
                );
            }

            form.append(
                `<input type="hidden" name="catatan" value="${result.value || ""}">`,
            );

            $("body").append(form);
            form.submit();
        }
    });
}

function showLoading(text = "Memproses...") {
    Swal.fire({
        title: text,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

// ================= EVENT (DELEGATION) =================
$(document).on("click", ".btn-submit", function () {
    let btn = $(this);
    let form = btn.closest("form");

    // 🔥 ambil action dari tombol
    let action = btn.data("action") || "save_all";

    // set ke hidden input
    form.find("#formAction").val(action);

    // 🔥 custom text biar beda
    let config = {
        title: "Simpan Data?",
        text: "Pastikan data sudah benar",
        icon: "question",
        confirmText: "Ya, Simpan",
    };

    if (action === "save_dokumen") {
        config.title = "Simpan Dokumen?";
        config.text = "Hanya dokumen yang akan disimpan";
    }

    showConfirm(config, function () {
        showLoading("Menyimpan...");

        btn.prop("disabled", true).html(
            '<i class="fas fa-spinner fa-spin"></i> Loading...',
        );

        form.submit();
    });
});

// ================= KEMBALI =================
$(document).on("click", ".btn-kembali", function (e) {
    e.preventDefault();

    let url = $(this).attr("href");

    showConfirm(
        {
            title: "Yakin kembali?",
            text: "Perubahan belum disimpan akan hilang",
            icon: "warning",
            confirmText: "Ya, Kembali",
        },
        function () {
            window.location.href = url;
        },
    );
});

// ================= HAPUS =================
$(document).on("click", ".btn-hapus", function () {
    let url = $(this).data("url");
    let csrf = $('meta[name="csrf-token"]').attr("content");

    showConfirm(
        {
            title: "Hapus Data?",
            text: "Data akan dipindahkan ke tempat sampah",
            icon: "warning",
            confirmText: "Ya, Hapus",
        },
        function () {
            showLoading("Menghapus...");

            let form = $("<form>", {
                method: "POST",
                action: url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);
            form.append(`<input type="hidden" name="_method" value="DELETE">`);

            $("body").append(form);
            form.submit();
        },
    );
});

// ================= RESTORE =================
$(document).on("click", ".btn-restore", function () {
    let url = $(this).data("url");
    let csrf = $('meta[name="csrf-token"]').attr("content");

    showConfirm(
        {
            title: "Pulihkan Data?",
            text: "Data akan dikembalikan kembali",
            icon: "question",
            confirmText: "Ya, Pulihkan",
        },
        function () {
            showLoading("Memulihkan...");

            let form = $("<form>", {
                method: "POST",
                action: url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);

            $("body").append(form);
            form.submit();
        },
    );
});

// ================= FORCE DELETE =================
$(document).on("click", ".btn-force-delete", function () {
    let url = $(this).data("url");
    let csrf = $('meta[name="csrf-token"]').attr("content");

    showConfirm(
        {
            title: "Hapus Permanen?",
            text: "Data akan dihapus SELAMANYA dan tidak bisa dikembalikan!",
            icon: "error",
            confirmText: "Ya, Hapus Permanen",
        },
        function () {
            showLoading("Menghapus permanen...");

            let form = $("<form>", {
                method: "POST",
                action: url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);
            form.append(`<input type="hidden" name="_method" value="DELETE">`);

            $("body").append(form);
            form.submit();
        },
    );
});

// ================= AJUKAN AK1 =================
$(document).on("click", ".btn-ajukan-ak1", function (e) {
    e.preventDefault();

    let url = $(this).data("url");

    showConfirm(
        {
            title: "Ajukan AK1?",
            text: "Setelah diajukan, status akan menjadi PENDING dan tidak bisa diedit sembarangan.",
            icon: "warning",
            confirmText: "Ya, Ajukan",
        },
        function () {
            showLoading("Mengajukan AK1...");

            let csrf = $('meta[name="csrf-token"]').attr("content");

            let form = $("<form>", {
                method: "POST",
                action: url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);

            $("body").append(form);
            form.submit();
        }
    );
});

function handleAjukan(url) {
    showConfirm(
        {
            title: "Ajukan AK1?",
            text: "Data akan dikirim untuk verifikasi petugas",
            icon: "question",
            confirmText: "Ya, Ajukan",
        },
        function () {
            showLoading("Mengajukan...");

            let csrf = $('meta[name="csrf-token"]').attr("content");

            let form = $("<form>", {
                method: "POST",
                action: url,
            });

            form.append(`<input type="hidden" name="_token" value="${csrf}">`);

            $("body").append(form);
            form.submit();
        }
    );
}

$(document).on("click", ".btn-approve", function () {
    confirmWithNote({
        url: $(this).data("url"),
        title: "Setujui Data?",
        text: "Catatan opsional",
        placeholder: "Catatan (opsional)...",
        confirmText: "Ya, Setujui",
        icon: "question",
        required: false,
    });
});

$(document).on("click", ".btn-reject", function () {
    confirmWithNote({
        url: $(this).data("url"),
        title: "Tolak Data",
        text: "Alasan wajib diisi",
        placeholder: "Masukkan alasan...",
        confirmText: "Tolak",
        icon: "warning",
        required: true,
    });
});

