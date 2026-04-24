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
        dom:
            "<'row mb-2'" +
            "<'col-md-6'l>" +
            "<'col-md-6'f>" +
            ">" +
            "rt" +
            "<'row mt-2'" +
            "<'col-md-6'i>" +
            "<'col-md-6'p>" +
            ">",
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
        if (settings.nTable.id !== "table-1") return true;

        let row = table.row(dataIndex).node();
        if (!row) return true;

        let status = $(row).data("status");
        let deleted = $(row).data("deleted");

        if (currentFilter === "all") return true;

        if (currentFilter === "pending")
            return status === "pending" && deleted == 0;

        if (currentFilter === "disetujui")
            return status === "disetujui" && deleted == 0;

        if (currentFilter === "ditolak")
            return status === "ditolak" && deleted == 0;

        if (currentFilter === "deleted") return deleted == 1;

        return true;
    });

    // ================= TAB ACTIVE =================
    function setActiveTab(filter) {
        $(".filter-tab").removeClass("active");
        $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
    }

    // ================= CLICK TAB =================
    $(".filter-tab").on("click", function (e) {
        e.preventDefault();

        currentFilter = $(this).data("filter");

        setActiveTab(currentFilter);
        table.draw();
    });

    // ================= DEFAULT =================
    setActiveTab("all");
    table.draw();
});
