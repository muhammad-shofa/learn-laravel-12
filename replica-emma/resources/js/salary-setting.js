import Swal from "sweetalert2";
// import AirDatepicker from "air-datepicker";
// import localeEn from "air-datepicker/locale/en";

$(document).ready(function () {
    // load all time off request data
    function loadSalarySettingsData() {
        $("#salarySettingTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/salary-setting/get-salary-settings",
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
                    data: null,
                    render: function (data, type, row) {
                        return data.employee
                            ? data.employee.employee_code
                            : "-";
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return data.employee ? data.employee.full_name : "-";
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return data.position
                            ? data.position.position_name
                            : "-";
                    },
                },
                {
                    data: "default_salary",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "effective_date",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-salary_setting_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-delete btn btn-danger" data-salary_setting_id="${row.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">
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

    // ambil data employee code untuk ditampilkan pada select
    function selectEmployeeCode(select_id) {
        // let employeeCodeSelect = $(select_id);
        let employeeCodeSelect = $("#employee_code");

        // Inisialisasi Select2 langsung dengan AJAX
        employeeCodeSelect.select2({
            theme: "bootstrap4",
            placeholder: "-- Select Employee --",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#addModal"),
            ajax: {
                url: "/api/employee/search-for-salary-setting",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // kata kunci pencarian
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map((employee) => ({
                            id: employee.id,
                            text: `${employee.employee_code} - ${employee.full_name}`,
                            disabled: employee.has_account == 0,
                        })),
                    };
                },
                cache: true,
            },
        });

        // jika ada option yang dipilih
        employeeCodeSelect.on("select2:select", function (e) {
            const employeeId = e.params.data.id;

            // Panggil API untuk ambil detail employee
            $.ajax({
                url: `/api/employee/get-employee/${employeeId}`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Misal response.data.position.position_name dan base_salary
                        $("#position_name")
                            .val(response.data.position.position_name)
                            .attr(
                                "data-position_id",
                                response.data.position.id
                            );

                        basic_salary_numeric.set(
                            response.data.position.base_salary
                        );
                    } else {
                        console.error(
                            "Failed to retrieved employee data:",
                            response.message
                        );
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },
            });
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

    // new AirDatepicker("#el", {
    //     locale: localeEn,
    // });

    // const datepicker = new AirDatepicker('#effective_date', {
    //     locale: "id",
    //     autoClose: true,
    //     dateFormat: 'yyyy-MM-dd',
    // });

    // const datepicker = new AirDatepicker('#effective_date', {
    //     // locale: 'id',
    //     dateFormat: 'yyyy-MM-dd',
    //     autoClose: true,
    // });

    // auto numeric library untuk inputan
    const basic_salary_numeric = new AutoNumeric("#base_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // auto numeric library untuk inputan
    const edit_basic_salary_numeric = new AutoNumeric("#edit_base_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // auto numeric library untuk inputan
    const default_salary_numeric = new AutoNumeric("#default_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // auto numeric library untuk inputan
    const edit_default_salary_numeric = new AutoNumeric("#edit_default_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    loadSalarySettingsData();
    selectEmployeeCode();

    // ketika tombol tambah diklik
    $(document).on("click", ".save-add", function () {
        const employee_id = $("#employee_code").val();
        const base_salary = basic_salary_numeric.getNumericString();
        const default_salary = default_salary_numeric.getNumericString();
        const effective_date = $("#effective_date").val();

        // validasi default salary tidak boleh lebih kecil dari base salary
        if (parseFloat(default_salary) < parseFloat(base_salary)) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Default salary cannot be less than base salary!",
            });
            return;
        }

        // validasi tanggal efektif tidak boleh di masa lalu
        const today = new Date();
        // kurangi 1 hari untuk menghindari masalah waktu
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        const effectiveDateObj = new Date(effective_date);

        if (effectiveDateObj <= yesterday) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "The effective date cannot be in the past!",
            });
            return;
        }

        // kirim data ke server
        $.ajax({
            url: "/api/salary-setting/add-salary-setting",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employee_id,
                position_id: $("#position_name").data("position_id"), // ambil dari data attribute
                default_salary: default_salary,
                effective_date: effective_date,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                    });
                    loadSalarySettingsData();
                    $("#addModal").modal("hide");
                    $("#addModal form")[0].reset();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi kesalahan saat menyimpan data.",
                });
            },
        });
    });

    // ketika tombol edit diklik
    $(document).on("click", ".btn-edit", function () {
        let salary_setting_id = $(this).data("salary_setting_id");

        // ambil data salary setting berdasarkan id
        $.ajax({
            url: "/api/salary-setting/get-salary-setting/" + salary_setting_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    $("#edit_salary_setting_id").val(data.id);
                    $("#edit_employee_code").val(data.employee.id);
                    $("#edit_position_name")
                        .val(data.position.position_name)
                        .attr("data-position_id", data.position.id);
                    edit_basic_salary_numeric.set(data.position.base_salary);
                    edit_default_salary_numeric.set(data.default_salary);
                    $("#edit_effective_date").val(data.effective_date);

                    // set employee code select2
                    $("#edit_employee_code").select2({
                        theme: "bootstrap4",
                        placeholder: "-- Select Employee --",
                        allowClear: true,
                        width: "100%",
                        dropdownParent: $("#editModal"),
                        data: [
                            {
                                id: data.employee.id,
                                text: `${data.employee.employee_code} - ${data.employee.full_name}`,
                            },
                        ],
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
        });
    });

    // ketika tombol save edit diklik
    $(document).on('click', '.save-edit', function () {
        const salary_setting_id = $("#edit_salary_setting_id").val();
        const employee_id = $("#edit_employee_code").val();
        const base_salary = edit_basic_salary_numeric.getNumericString();
        const default_salary = edit_default_salary_numeric.getNumericString();
        const effective_date = $("#edit_effective_date").val();

        // validasi default salary tidak boleh lebih kecil dari base salary
        if (parseFloat(default_salary) < parseFloat(base_salary)) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Default salary cannot be less than base salary!",
            });
            return;
        }

        // validasi tanggal efektif tidak boleh di masa lalu
        const today = new Date();
        // kurangi 1 hari untuk menghindari masalah waktu
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        const effectiveDateObj = new Date(effective_date);

        if (effectiveDateObj <= yesterday) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "The effective date cannot be in the past!",
            });
            return;
        }

        // kirim data ke server
        $.ajax({
            url: "/api/salary-setting/update-salary-setting/" + salary_setting_id,
            type: "PUT",
            dataType: "json",
            data: {
                employee_id: employee_id,
                default_salary: default_salary,
                effective_date: effective_date,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                    });
                    loadSalarySettingsData();
                    $("#editModal").modal("hide");
                    $("#editModal form")[0].reset();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi kesalahan saat menyimpan data.",
                });
            },
        });
    });

    // ketika tombol hapus diklik
    $(document).on("click", ".btn-delete", function () {
        let salary_setting_id = $(this).data("salary_setting_id");
        $("#delete_salary_setting_id").val(salary_setting_id);
    });

    // ketika tombol konfirmasi hapus diklik
    $(document).on("click", ".confirmed-delete", function () {
        let salary_setting_id = $("#delete_salary_setting_id").val();

        $.ajax({
            url:
                "/api/salary-setting/delete-salary-setting/" +
                salary_setting_id,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#deleteModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been deleted successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadSalarySettingsData();
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
