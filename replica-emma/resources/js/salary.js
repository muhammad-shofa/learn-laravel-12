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

        // jika ada option yang dipilih
        employeeCodeSelect.on("select2:select", function (e) {
            const employeeId = e.params.data.id;

            // Panggil API untuk ambil detail employee
            $.ajax({
                url: `/api/employee/get-employee-for-salary/${employeeId}`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        
                    } else {
                        console.error(
                            "Failed to retrieved data:",
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

    loadSalaryData();
    selectEmployeeCode();

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
