let table;
let isPrinting = false;

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
    table.on("draw.dt", function () {
        let info = table.page.info();

        table
            .column(0, { page: "current" })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
    });

    // ================= FILTER =================
    let currentFilter = "all";

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== "table-1") {
            return true;
        }

        let row = table.row(dataIndex).node();

        if (!row) {
            return true;
        }

        let isDeleted = $(row).data("deleted") == 1;
        let isAktif = $(row).data("deleted") == 0;

        if (currentFilter === "all") {
            return true;
        }

        if (currentFilter === "aktif") {
            return isAktif;
        }

        if (currentFilter === "deleted") {
            return isDeleted;
        }

        return true;
    });

    // ================= TAB FILTER =================
    function setActiveTab(filter) {
        $(".filter-tab").removeClass("active");

        $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
    }

    $(".filter-tab").on("click", function (e) {
        e.preventDefault();

        currentFilter = $(this).data("filter");

        setActiveTab(currentFilter);

        table.draw();
    });

    // ================= PRINT =================
    $(".btn-print").on("click", function (e) {
        e.preventDefault();

        if (isPrinting) {
            return;
        }

        isPrinting = true;

        const iframe = document.createElement("iframe");

        iframe.style.position = "fixed";
        iframe.style.right = "0";
        iframe.style.bottom = "0";
        iframe.style.width = "0";
        iframe.style.height = "0";
        iframe.style.border = "0";
        iframe.style.visibility = "hidden";

        iframe.src = "/admin/perusahaan/laporan-pencari-kerja/print";

        iframe.onload = function () {
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                setTimeout(() => {
                    document.body.removeChild(iframe);

                    isPrinting = false;
                }, 1500);
            }, 400);
        };

        document.body.appendChild(iframe);
    });

    // ================= INIT =================
    setActiveTab("all");

    table.draw();
});
