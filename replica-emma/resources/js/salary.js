import Swal from "sweetalert2";

$(document).ready(function () {
    // load all time off request data
    function loadSalaryData() {
        $("#salaryTableData").DataTable({
            destroy: true,
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

    function loadPercentageTargetWorkDuration() {
        let employee_id = $("#salary_employee_id").val();

        $.ajax({
            url: `/api/salary/get-percentage-target-work-duration/${employee_id}`,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    var options = {
                        series: [response.percentage],
                        chart: {
                            width: 380,
                            height: 400,
                            type: "radialBar",
                            offsetY: -10,
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -135,
                                endAngle: 135,
                                dataLabels: {
                                    name: {
                                        fontSize: "16px",
                                        color: undefined,
                                        offsetY: 120,
                                    },
                                    value: {
                                        offsetY: 76,
                                        fontSize: "22px",
                                        color: undefined,
                                        formatter: function (val) {
                                            return response.percentage + "%";
                                        },
                                    },
                                },
                            },
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                shade: "dark",
                                shadeIntensity: 0.15,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [0, 50, 65, 91],
                            },
                        },
                        stroke: {
                            dashArray: 4,
                        },
                        labels: ["Completed"],
                    };

                    var chart = new ApexCharts(
                        document.querySelector("#salaryAttandanceChart"),
                        options
                    );
                    chart.render();

                    // update info tambahan
                    $("#statusText").text(
                        response.completed_hours < response.target_hours
                            ? "Ongoing"
                            : "Completed"
                    );
                    $("#percentageText").text(response.percentage + "%");
                    $("#completedHours").text(
                        response.completed_hours + " Hours"
                    );
                    $("#targetHours").text(response.target_hours + " Hours");
                    $("#remainingHours").text(
                        response.remaining_hours + " Hours"
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
        });
    }

    function loadSalaryTimeOff() {
        let employee_id = $("#salary_employee_id").val();

        $.ajax({
            url: `/api/salary/get-salary-time-off/${employee_id}`,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    var options = {
                        series: response.data,
                        chart: {
                            height: 430,
                            type: "bar",
                            stacked: true,
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                dataLabels: {
                                    position: "top",
                                },
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        xaxis: {
                            categories: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sep",
                                "Oct",
                                "Nov",
                                "Dec",
                            ],
                        },
                        yaxis: {
                            title: {
                                text: "Jumlah Time Off",
                            },
                        },
                        legend: {
                            position: "top",
                        },
                        title: {
                            text: "Time Off Requests per Month",
                            align: "center",
                            style: { fontSize: "16px" },
                        },
                    };

                    var chart = new ApexCharts(
                        document.querySelector("#salaryTimeOffChart"),
                        options
                    );
                    chart.render();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
        });
    }

    function loadSalaryDataForEmployee() {
        let employee_id = $("#salary_employee_id").val();
        $("#employeeSalaryTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: `/api/salary/get-salary/${employee_id}`,
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
                        return data.employee ? data.employee.full_name : "-";
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
                        year: $("#year").val(),
                        month: $("#month").val(),
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map((employee) => ({
                            id: employee.id,
                            text: `${employee.employee_code} - ${employee.full_name}`,
                            disabled: employee.disabled,
                            // disabled: employee.has_account == 0,
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
                            total_deduction_numeric.set(
                                response.deduction_amount || 0
                            );
                            bonus_numeric.set(response.overtime_bonus || 0);
                            total_salary_numeric.set(
                                response.total_salary || 0
                            );

                            // detail offcanvas
                            $("#detail_total_work_duration").text(
                                response.total_work_duration + " hours"
                            );
                            $("#detail_standard_duration").text(
                                response.standard_duration + " hours"
                            );
                            $("#detail_difference").text(
                                response.difference + " hours"
                            );
                            detail_missing_hours_deduction_numeric.set(
                                response.missing_hours_deduction
                            );
                            $("#detail_overtime_hours").text(
                                response.overtime_hours + " hours"
                            );
                            detail_overtime_bonus_numeric.set(
                                response.overtime_bonus
                            );
                            $("#detail_absent_days").text(response.absent_days);
                            detail_absent_deduction_numeric.set(
                                response.absent_deduction
                            );
                            detail_total_deduction_numeric.set(
                                response.deduction_amount
                            );
                            detail_total_salary_numeric.set(
                                response.total_salary
                            );
                        } else {
                            total_deduction_numeric.set(0);
                            bonus_numeric.set(0);
                            total_salary_numeric.set(0);

                            if (response.isDoubleData) {
                                // Kosongkan select employee
                                employeeCodeSelect.val(null).trigger("change");

                                // Optional: tampilkan notifikasi ke admin
                                Swal.fire({
                                    icon: "warning",
                                    title: "Error",
                                    text:
                                        response.message ||
                                        "Failed to fetch salary data.",
                                });
                            }
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
    loadPercentageTargetWorkDuration();
    loadSalaryTimeOff();
    loadSalaryDataForEmployee();
    selectEmployeeCode();

    // menggunakan pengecekan if karena jika tidak ada elemen dengan id tersebut, AutoNumeric akan error
    let total_deduction_numeric = null;
    if ($("#total_salary").length) {
        total_deduction_numeric = new AutoNumeric("#total_deduction", {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        });
    }

    let bonus_numeric = null;
    if ($("#bonus").length) {
        bonus_numeric = new AutoNumeric("#bonus", {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        });
    }

    let total_salary_numeric = null;
    if ($("#total_deduction").length) {
        total_salary_numeric = new AutoNumeric("#total_salary", {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        });
    }

    // value detail modal
    // detail mossoing hours deduction
    let detail_missing_hours_deduction_numeric = null;
    if ($("#detail_total_work_duration").length) {
        detail_missing_hours_deduction_numeric = new AutoNumeric(
            "#detail_missing_hours_deduction",
            {
                digitGroupSeparator: ".",
                decimalCharacter: ",",
                decimalPlaces: 0,
                currencySymbol: "Rp ",
                currencySymbolPlacement: "p",
                modifyValueOnWheel: false,
            }
        );
    }

    // detail ovettime bonus
    let detail_overtime_bonus_numeric = null;
    if ($("#detail_overtime_bonus").length) {
        detail_overtime_bonus_numeric = new AutoNumeric(
            "#detail_overtime_bonus",
            {
                digitGroupSeparator: ".",
                decimalCharacter: ",",
                decimalPlaces: 0,
                currencySymbol: "Rp ",
                currencySymbolPlacement: "p",
                modifyValueOnWheel: false,
            }
        );
    }

    // detail absent deduction
    let detail_absent_deduction_numeric = null;
    if ($("#detail_absent_deduction").length) {
        detail_absent_deduction_numeric = new AutoNumeric(
            "#detail_absent_deduction",
            {
                digitGroupSeparator: ".",
                decimalCharacter: ",",
                decimalPlaces: 0,
                currencySymbol: "Rp ",
                currencySymbolPlacement: "p",
                modifyValueOnWheel: false,
            }
        );
    }

    // detail total deduction
    let detail_total_deduction_numeric = null;
    if ($("#detail_total_deduction").length) {
        detail_total_deduction_numeric = new AutoNumeric(
            "#detail_total_deduction",
            {
                digitGroupSeparator: ".",
                decimalCharacter: ",",
                decimalPlaces: 0,
                currencySymbol: "Rp ",
                currencySymbolPlacement: "p",
                modifyValueOnWheel: false,
            }
        );
    }

    // detail total salary
    let detail_total_salary_numeric = null;
    if ($("#detail_total_salary").length) {
        detail_total_salary_numeric = new AutoNumeric("#detail_total_salary", {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        });
    }

    // pilih tipe generate salary
    $("#salary_method").on("change", function () {
        const selected = $(this).val();
        if (selected === "auto") {
            $("#manualSalaryFields").hide();
        } else {
            $("#manualSalaryFields").show();
        }
    });

    // Trigger agar default-nya muncul
    $("#salary_method").trigger("change");

    // ketika tombol generate salary diklik
    // v2
    $(document).on("click", ".generate-salary-btn", function () {
        let method = $("#salary_method").val();

        // default data
        let data = {
            method: method,
            payment_date: new Date().toISOString().split("T")[0],
        };

        // jika manual, ambil data form
        if (method === "manual") {
            data.employee_id = $("#employee_code").val();
            data.year = $("#year").val();
            data.month = $("#month").val();
            data.hour_deduction =
                detail_missing_hours_deduction_numeric.getNumericString();
            data.absent_deduction =
                detail_absent_deduction_numeric.getNumericString();
            data.deduction = total_deduction_numeric.getNumericString();
            data.bonus = bonus_numeric.getNumericString();
            data.total_salary = total_salary_numeric.getNumericString();
        }

        $.ajax({
            url: "/api/salary/generate-salary",
            type: "POST",
            dataType: "json",
            data: data,
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
                    loadSalaryData();
                    $("#addModal").modal("hide");
                    $("#offcanvasSalaryDetail").hide();
                    $(".offcanvas-backdrop").remove();
                    $("#employee_code").val(null).trigger("change");
                    total_deduction_numeric.set(0);
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
                        responseType: "blob",
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
