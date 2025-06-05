import Swal from "sweetalert2";
// import AirDatepicker from "air-datepicker";
// import localeEn from "air-datepicker/locale/en";

$(document).ready(function () {
    // load all time off request data
    function loadSalaryData() {
        $("#salaryTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/salary/get-salaries",
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
                        return data.employee.position
                            ? data.employee.position.position_name
                            : "-";
                    },
                },
                {
                    data: "year",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: "month",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: "deduction",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "bonus",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "total_salary",
                    render: function (data, type, row) {
                        return data ? formatRupiah(data) : "-";
                    },
                },
                {
                    data: "payment_date",
                    render: function (data, type, row) {
                        return data ?? "-";
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-download-pdf btn btn-warning" data-salary_id="${row.id}" data-bs-toggle="modal">
                                <i class="fa-solid fa-download"></i>
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
                url: "/api/employee/search-for-salary",
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

        // inisialisasi variable untuk option yang dipilih
        let selected_employee_id = null;
        let selected_year = null;
        let selected_month = null;

        // cek dan ambil data salary yang dibutuhan untuk menambah salary
        function checkAndFetchSalary() {
            if (selected_employee_id && selected_year && selected_month) {
                $.ajax({
                    url: `/api/employee/get-employee-for-salary/${selected_employee_id}/${selected_year}/${selected_month}`,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            deduction_numeric.set(
                                response.deduction_amount || 0
                            );
                            bonus_numeric.set(response.overtime_bonus || 0);
                            total_salary_numeric.set(
                                response.total_salary || 0
                            );
                        } else {
                            // console.error(
                            //     "Failed to retrieve data:",
                            //     response.message
                            // );
                            deduction_numeric.set(0);
                            bonus_numeric.set(0);
                            total_salary_numeric.set(0);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    },
                });
            }
        }

        // jika ada option yang dipilih
        employeeCodeSelect.on("select2:select", function (e) {
            selected_employee_id = e.params.data.id;
            checkAndFetchSalary();
            console.log(selected_employee_id);
        });

        // ketika tahun dipilih
        $("#year").on("change", function () {
            selected_year = $("#year").val();
            checkAndFetchSalary();
            console.log(selected_year);
        });

        // ketika bulan dipilih
        $("#month").on("change", function () {
            selected_month = $("#month").val();
            checkAndFetchSalary();
            console.log(selected_month);
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

    loadSalaryData();
    selectEmployeeCode();

    const deduction_numeric = new AutoNumeric("#deduction", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    const bonus_numeric = new AutoNumeric("#bonus", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    const total_salary_numeric = new AutoNumeric("#total_salary", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: 0,
        currencySymbol: "Rp ",
        currencySymbolPlacement: "p",
        modifyValueOnWheel: false,
    });

    // ketika tombol generate salary diklik
    $(document).on("click", ".generate-salary-btn", function () {
        // ambil data dari form
        let employee_id = $("#employee_code").val();
        let year = $("#year").val();
        let month = $("#month").val();
        let deduction = deduction_numeric.getNumericString();
        let bonus = bonus_numeric.getNumericString();
        let total_salary = total_salary_numeric.getNumericString();
        let payment_date = new Date();
        let formatted_payment_date = payment_date.toISOString().split("T")[0];

        $.ajax({
            url: "/api/salary/generate-salary",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employee_id,
                year: year,
                month: month,
                deduction: deduction,
                bonus: bonus,
                total_salary: total_salary,
                payment_date: formatted_payment_date,
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
                    // reload data table
                    loadSalaryData();
                    // reset form
                    $("#addModal").modal("hide");
                    $("#employee_code").val(null).trigger("change");
                    deduction_numeric.set(0);
                    bonus_numeric.set(0);
                    total_salary_numeric.set(0);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to generate salary. Please try again.",
                });
            },
        });
    });

    // ketika tomobl download pdf diklik
    $(document).on("click", ".btn-download-pdf", function () {
        const salary_id = $(this).data("salary_id");

        // tampilkan sweetalert konfirmasi
        Swal.fire({
            title: "Download PDF",
            text: "Are you sure you want to download this salary slip?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Download!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/salary/download-pdf/${salary_id}`,
                    type: "GET",
                    xhrFields: {
                        responseType: "blob", // untuk menangani file binary
                    },
                    success: function (data, status, xhr) {
                        // buat link untuk download
                        const blob = new Blob([data], {
                            type: xhr.getResponseHeader("Content-Type"),
                        });
                        const link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        link.download = `salary-slip-${salary_id}.pdf`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    error: function (xhr, status, error) {
                        console.error("Download failed:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Download Failed",
                            text: "There was an error downloading the salary slip.",
                        });
                    },
                });
            }
        });
    });
});
