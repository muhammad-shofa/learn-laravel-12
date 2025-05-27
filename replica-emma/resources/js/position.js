import Swal from "sweetalert2";

$(document).ready(function () {
    // load all time off request data
    function loadPositionsData() {
        $("#positionTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/position/get-positions",
                type: "GET",
                dataSrc: function (response) {
                    if (response.success) {
                        return response.data;
                    } else {
                        console.error(response.error);
                        return [];
                    }
                },
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                },
                {
                    data: "position_name",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                // { data: "description" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-detail-description btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailDescriptionModal" data-position_id="${row.id}">
                                <i class="fa-solid fa-eye"></i>
                            </button>`;
                    },
                },
                {
                    data: "hourly_rate",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "annual_salary_increase",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: "base_salary",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-position_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-delete btn btn-danger" data-position_id="${row.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    },
                },
            ],
            columnDefs: [
                {
                    targets: "_all", // semua kolom
                    className: "text-start align-middle",
                },
            ],
        });
    }

    // format angka ke rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    }

    loadPositionsData();

    // ketika tombol mata / detail di klik
    $(document).on("click", ".btn-detail-description", function () {
        let position_id = $(this).data("position_id");
        $.ajax({
            url: "/api/position/get-position/" + position_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#position-description-field").text(
                        response.data.description
                    );
                } else {
                    console.log(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // auto numeric library untuk inputan add currency
    const hourly_rate_numeric = new AutoNumeric("#hourly_rate", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    const base_salary_numeric = new AutoNumeric("#base_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // auto numeric library untuk inputan edit currency
    const edit_hourly_rate_numeric = new AutoNumeric("#edit_hourly_rate", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    const edit_base_salary_numeric = new AutoNumeric("#edit_base_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // simpan data position
    $(document).on("click", ".save-add", function () {
        let position_name = $("#position_name").val();
        let description = $("#description").val();
        let hourly_rate = hourly_rate_numeric.getNumber();
        let annual_salary_increase = $("#annual_salary_increase").val();
        let base_salary = base_salary_numeric.getNumber();
        let status = $("#status").val();

        $.ajax({
            url: "/api/position/add-position",
            type: "POST",
            dataType: "json",
            data: {
                position_name: position_name,
                description: description,
                hourly_rate: hourly_rate,
                annual_salary_increase: annual_salary_increase,
                base_salary: base_salary,
                status: status,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#addModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been added successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadPositionsData();
                    $("#addPositionForm")[0].reset();
                } else {
                    $("#addModal").modal("hide");
                    loadPositionsData();
                    $("#addPositionForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Failed!",
                    text: "Failed to add new data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol edit diklik
    $(document).on("click", ".btn-edit", function () {
        let position_id = $(this).data("position_id");
        $.ajax({
            url: "/api/position/get-position/" + position_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#edit_position_id").val(response.data.id);
                    $("#edit_position_name").val(response.data.position_name);
                    $("#edit_description").val(response.data.description);
                    edit_hourly_rate_numeric.set(response.data.hourly_rate);
                    $("#edit_annual_salary_increase").val(
                        response.data.annual_salary_increase
                    );
                    edit_base_salary_numeric.set(response.data.base_salary);
                    $("#edit_status").val(response.data.status);
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol save edit diklik
    $(document).on("click", ".save-edit", function () {
        let position_id = $("#edit_position_id").val();
        let position_name = $("#edit_position_name").val();
        let description = $("#edit_description").val();
        let hourly_rate = edit_hourly_rate_numeric.getNumber();
        let annual_salary_increase = $("#edit_annual_salary_increase").val();
        let base_salary = edit_base_salary_numeric.getNumber();
        let status = $("#edit_status").val();

        $.ajax({
            url: "/api/position/update-position/" + position_id,
            type: "PUT",
            dataType: "json",
            data: {
                position_name: position_name,
                description: description,
                hourly_rate: hourly_rate,
                annual_salary_increase: annual_salary_increase,
                base_salary: base_salary,
                status: status,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#editModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been updated successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadPositionsData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Failed!",
                    text: "Failed to update data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });

    // ketika tombol hapus diklik
    $(document).on("click", ".btn-delete", function () {
        let position_id = $(this).data("position_id");
        console.log(position_id);
        $("#deleteModal").modal("show");
        $("#delete_position_id").val(position_id);
    });

    // ketika tombol konfirmasi hapus diklik
    $(document).on("click", ".confirmed-delete", function () {
        let position_id = $("#delete_position_id").val();

        $.ajax({
            url: "/api/position/delete-position/" + position_id,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    // console.log(response.message);
                    $("#deleteModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been deleted successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadPositionsData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Failed to delete data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
