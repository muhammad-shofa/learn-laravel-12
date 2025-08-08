import Swal from "sweetalert2";

$(document).ready(function () {
    function loadDashboardData() {
        $.ajax({
            url: "/api/dashboard/get-all-dashboard-data",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    let attendacesDashboardTable = $(
                        "#attendanceDashboardTableData tbody"
                    );
                    let no = 0;

                    attendacesDashboardTable.empty();
                    $("#employee_counts").text(response.employee_counts);
                    $("#late_counts").text(response.late_counts);
                    $("#time_off_counts").text(response.time_off_counts);
                    $.each(
                        response.attendance_latest_three,
                        (index, attendance_latest) => {
                            no++;
                            attendacesDashboardTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${attendance_latest.employee.full_name}</td>
                                <td>${attendance_latest.date}</td>
                                <td>${attendance_latest.clock_in}</td>
                                <td>${
                                    attendance_latest.clock_in_status ==
                                    "ontime"
                                        ? '<span class="badge text-bg-success">' +
                                          attendance_latest.clock_in_status +
                                          "</span>"
                                        : '<span class="badge text-bg-danger">' +
                                          attendance_latest.clock_in_status +
                                          "</span>"
                                }</td>
                                <td>${
                                    attendance_latest.clock_out_status ==
                                    "ontime"
                                        ? '<span class="badge text-bg-success">' +
                                          attendance_latest.clock_out_status +
                                          "</span>"
                                        : '<span class="badge text-bg-danger">' +
                                          attendance_latest.clock_out_status +
                                          "</span>"
                                }</td>
                            <tr>
                            `);
                        }
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    }

    // load monthly data chart
    function loadMonthlyChart() {
        $.ajax({
            url: "/api/dashboard/monthly-chart",
            type: "GET",
            dataType: "json",
            success: function (response) {
                // options
                var options = {
                    series: [
                        {
                            name: "Attendance",
                            data: response.data.attendance,
                        },
                        {
                            name: "Ontime",
                            data: response.data.ontime,
                        },
                        {
                            name: "Late",
                            data: response.data.late,
                        },
                        {
                            name: "Time Off Approved",
                            data: response.data.timeoff_approve,
                        },
                        {
                            name: "Time Off Rejected",
                            data: response.data.timeoff_reject,
                        },
                        {
                            name: "Time Off Pending",
                            data: response.data.timeoff_pending,
                        },
                    ],
                    chart: {
                        height: 350,
                        type: "area",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                    },
                    xaxis: {
                        title: { text: "Month" },
                        categories: response.labels,
                        // type: "datetime",
                    },
                    tooltip: {
                        x: {
                            format: "dd/MM/yy HH:mm",
                        },
                    },
                };

                var chart = new ApexCharts(
                    document.querySelector("#monthlyAttendanceChart"),
                    options
                );
                chart.render();
            },
            error: function (xhr, status, error) {
                console.error("Gagal load ApexChart: ", error);
            },
        });
    }

    // load attendance in employee dashboard
    function loadEmployeeAttendanceData() {
        let employee_id = $("#edit_employee_id").val();
        $("#dashboardAttendanceTableData").DataTable({
            destroy: true,
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/attendance/get-employee-attendance/" + employee_id,
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
                { data: "date" },
                {
                    data: "clock_in",
                    render: (data) => data ?? "-",
                },
                {
                    data: "clock_out",
                    render: (data) => data ?? "-",
                },
                {
                    data: "clock_in_status",
                    render: function (data, type, row) {
                        if (type === "display") {
                            // Tentukan warna badge berdasar isi status
                            let badgeClass = "text-bg-secondary"; // default abu-abu
                            if (data === "ontime")
                                badgeClass = "text-bg-success";
                            if (data === "late") badgeClass = "text-bg-danger";
                            if (data === "absent")
                                badgeClass = "text-bg-danger";
                            if (data === "leave")
                                badgeClass = "text-bg-warning";

                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        // untuk sorting / searching gunakan nilai mentah
                        return data;
                    },
                },
                {
                    data: "clock_out_status",
                    render: function (data, type, row) {
                        if (type === "display") {
                            // Tentukan warna badge berdasar isi status
                            let badgeClass = "text-bg-secondary"; // default abu-abu
                            if (data === "ontime")
                                badgeClass = "text-bg-success";
                            if (data === "early")
                                badgeClass = "text-bg-warning";
                            if (data === "late") badgeClass = "text-bg-danger";
                            if (data === "no_clock_out")
                                badgeClass = "text-bg-danger";

                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        // untuk sorting / searching gunakan nilai mentah
                        return data;
                    },
                },
                {
                    data: "work_duration",
                    render: (data) => data ?? "-",
                },
            ],
            columnDefs: [
                {
                    targets: "_all",
                    className: "text-start align-middle",
                },
            ],
        });
    }

    function loadEmployeeTimeOffData() {
        let employee_id = $("#edit_employee_id").val();
        $.ajax({
            url:
                "/api/time-off/get-time-off-request-employee-id/" + employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#employee_total_quota").text(
                        response.data[0].employee.time_off_quota + " Day"
                    );
                    $("#employee_used_quota").text(
                        response.data[0].employee.time_off_used + " Day"
                    );
                    $("#employee_remaining_quota").text(
                        response.data[0].employee.time_off_remaining + " Day"
                    );
                    $("#employee_last_time_off").text(
                        response.data[0].end_date
                    );
                }
            },
        });
    }

    loadDashboardData();
    loadMonthlyChart();
    loadEmployeeAttendanceData();
    loadEmployeeTimeOffData();

    // reset filter
    $(document).on("click", ".btn-reset-filter", () => {
        loadDashboardData();
        loadMonthlyChart();
    });

    // apply filter
    $(document).on("click", ".btn-apply-filter", () => {
        const month = $("#filter_month").val();
        const year = $("#filter_year").val();
        console.log(month);
        console.log(year);

        $.ajax({
            url: "/api/dashboard/filter-dashboard-data",
            type: "GET",
            data: { month: month, year: year },
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let attendacesDashboardTable = $(
                        "#attendanceDashboardTableData tbody"
                    );
                    let no = 0;

                    $("#filterModal").modal("hide");

                    attendacesDashboardTable.empty();
                    $("#employee_counts").text(response.employee_counts);
                    $("#late_counts").text(response.late_counts);
                    $("#time_off_counts").text(response.time_off_counts);
                    $.each(
                        response.attendance_latest_three,
                        (index, attendance_latest) => {
                            no++;
                            attendacesDashboardTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${attendance_latest.employee.full_name}</td>
                                <td>${attendance_latest.date}</td>
                                <td>${attendance_latest.clock_in}</td>
                                <td>${
                                    attendance_latest.clock_in_status ==
                                    "ontime"
                                        ? '<span class="badge text-bg-success">' +
                                          attendance_latest.clock_in_status +
                                          "</span>"
                                        : '<span class="badge text-bg-danger">' +
                                          attendance_latest.clock_in_status +
                                          "</span>"
                                }</td>
                                <td>${
                                    attendance_latest.clock_out_status ==
                                    "ontime"
                                        ? '<span class="badge text-bg-success">' +
                                          attendance_latest.clock_out_status +
                                          "</span>"
                                        : '<span class="badge text-bg-danger">' +
                                          attendance_latest.clock_out_status +
                                          "</span>"
                                }</td>
                            </tr>
                        `);
                        }
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("Filter AJAX Error: " + status + error);
            },
        });
    });

    // ketika edit employee data diklik
    $(document).on("click", ".btn-show-edit-employee-modal", function () {
        let employee_id = $(this).data("employee_id");
        $.ajax({
            url: "/api/employee/get-employee/" + employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#editModal").modal("show");
                    // $("#addModal").modal("hide");

                    $("#edit_employee_email").val(response.data.email);
                    $("#edit_employee_phone").val(response.data.phone);
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika save edit employee data diklik
    $(document).on("click", ".btn-edit-employee-data", () => {
        let edit_employee_id = $("#edit_employee_id").val();
        let edit_employee_email = $("#edit_employee_email").val();
        let edit_employee_phone = String($("#edit_employee_phone").val());

        console.log(edit_employee_email);
        console.log(edit_employee_phone);
        $.ajax({
            url: "/api/dashboard/edit-employee-data",
            type: "PUT",
            dataType: "json",
            data: {
                id: edit_employee_id,
                email: edit_employee_email,
                phone: edit_employee_phone,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    $("#editModal").modal("hide");
                    $("#employee_email_data").text(edit_employee_email);
                    $("#employee_phone_data").text(edit_employee_phone);
                    $("#editEmployeeForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("Edit Employee AJAX Error: " + status + error);
            },
        });
    });

    // ketika show password diklik
    $(document).on("click", "#show-old-password", function () {
        let oldPasswordInput = $("#old_password");
        if (oldPasswordInput.attr("type") === "password") {
            oldPasswordInput.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            oldPasswordInput.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye"></i>');
        }
    });

    $(document).on("click", "#show-new-password", function () {
        let newPasswordInput = $("#new_password");
        if (newPasswordInput.attr("type") === "password") {
            newPasswordInput.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            newPasswordInput.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye"></i>');
        }
    });

    $(document).on("click", "#show-confirm-password", function () {
        let confirmPasswordInput = $("#confirm_password");
        if (confirmPasswordInput.attr("type") === "password") {
            confirmPasswordInput.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            confirmPasswordInput.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye"></i>');
        }
    });

    // ketika tomgol reset diklilk
    $(document).on("click", ".btn-reset-password", function (e) {
        e.preventDefault();
        let employee_id = $("#employee_id").val();
        let old_password = $("#old_password").val();
        let new_password = $("#new_password").val();
        let confirm_password = $("#confirm_password").val();

        // console.log({
        //     employee_id: employee_id,
        //     old_password: old_password,
        //     new_password: new_password,
        //     confirm_password: confirm_password,
        // });

        if (new_password != confirm_password) {
            Swal.fire({
                title: "Password do not match!",
                text: "Please ensure the new password and confirmation match.",
                icon: "error",
                confirmButtonText: "Oke",
            });

            return;
        }

        $.ajax({
            url: "/api/dashboard/reset-employee-password",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employee_id,
                old_password: old_password,
                new_password: new_password,
                confirm_password: confirm_password,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    $("#resetPasswordForm")[0].reset();
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "Oke",
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Reset Password AJAX Error: " + status + error);
            },
        });
    });
});
