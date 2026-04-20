$(document).ready(function () {
    // ================= DATATABLE (INDEX SAJA) =================
    if ($("#table-1").length) {
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

        function applyFilter(filter) {
            $.fn.dataTable.ext.search = [];

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    let row = table.row(dataIndex).node();
                    let status = $(row).data("status");

                    if (filter === "all") return true;
                    if (filter === "dikirim") return status === "dikirim";
                    if (filter === "diproses") return status === "diproses";
                    if (filter === "diterima") return status === "diterima";
                    if (filter === "ditolak") return status === "ditolak";

                    return true;
                },
            );

            table.draw();
        }

        function setActiveTab(filter) {
            $(".filter-tab").removeClass("active");
            $('.filter-tab[data-filter="' + filter + '"]').addClass("active");
        }

        $(".filter-tab").on("click", function (e) {
            e.preventDefault();

            let filter = $(this).data("filter");

            setActiveTab(filter);
            applyFilter(filter);
        });

        setActiveTab("all");
        applyFilter("all");
    }

    // ================= TOGGLE CUSTOM DOKUMEN =================
    function toggleCustomDocInput(selectElement) {
        let $select = $(selectElement);
        let $row = $select.closest("tr");
        let $customInput = $row.find(".custom-doc-input");

        if ($select.val() === "lainnya") {
            $customInput.show().prop("required", true);
        } else {
            $customInput.hide().val("").prop("required", false);
        }
    }

    // saat halaman load, cek semua select yang sudah ada
    $(".doc-type-select").each(function () {
        toggleCustomDocInput(this);
    });

    // saat select berubah
    $(document).on("change", ".doc-type-select", function () {
        toggleCustomDocInput(this);
    });

    // ================= ADD / REMOVE ROW =================
    if ($("#add-row").length && $("#dokumen-wrapper").length) {
        $("#add-row").on("click", function () {
            let wrapper = $("#dokumen-wrapper");

            let row = `
                <tr class="dokumen-row">
                    <td>
                        <select name="jenis_dokumen[]" class="form-control form-control-sm rounded-lg doc-type-select">
                            <option value="">-- Pilih Dokumen --</option>
                            <option value="cv">CV</option>
                            <option value="surat_lamaran">Surat Lamaran</option>
                            <option value="ktp">KTP</option>
                            <option value="ijazah">Ijazah</option>
                            <option value="transkrip">Transkrip</option>
                            <option value="sertifikat">Sertifikat</option>
                            <option value="foto">Pas Foto</option>
                            <option value="lainnya">LAINNYA</option>
                        </select>

                        <input type="text"
                            name="jenis_dokumen_custom[]"
                            class="form-control form-control-sm mt-2 custom-doc-input"
                            placeholder="Nama dokumen custom..."
                            style="display:none;">
                    </td>

                    <td>
                        <input type="file"
                            name="lokasi_file[]"
                            class="form-control form-control-sm file-input-small rounded-lg"
                            accept=".pdf,.jpg,.jpeg,.png">
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            wrapper.append(row);
        });

        $(document).on("click", ".remove-row", function () {
            $(this).closest("tr").remove();
        });
    }
});
