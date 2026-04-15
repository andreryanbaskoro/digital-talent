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
        .on("order.dt search.dt draw.dt", function () {
            table
                .column(0, { search: "applied", order: "applied" })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        })
        .draw();

    // ================= FILTER FUNCTION =================
    let currentFilter = "all";

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        // pastikan hanya berlaku untuk table ini
        if (settings.nTable.id !== "table-1") {
            return true;
        }

        let row = table.row(dataIndex).node();
        let deleted = $(row).data("deleted") ?? 0;

        if (currentFilter === "all") return true;
        if (currentFilter === "deleted") return deleted == 1;

        return true;
    });

    // ================= TAB HANDLER =================
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

    // ================= DEFAULT =================
    setActiveTab("all");
    table.draw();
});
