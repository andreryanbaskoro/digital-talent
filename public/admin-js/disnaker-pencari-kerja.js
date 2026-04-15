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
            let deleted = $(row).data("deleted");

            if (filter === "all") return true;
            if (filter === "deleted") return deleted == 1;

            return true;
        });

        table.draw();
    }

    // set tab aktif
    function setActiveTab(filter) {
        $(".filter-tab").removeClass("active");
        $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
    }

    // klik tab
    $(".filter-tab").on("click", function (e) {
        e.preventDefault();

        let filter = $(this).data("filter");

        setActiveTab(filter);
        applyFilter(filter);
    });

    // ================= DEFAULT =================
    // Set tab "all" sebagai default
    setActiveTab("all");
    applyFilter("all");
});
