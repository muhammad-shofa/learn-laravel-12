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

    loadDashboardData();
    loadMonthlyChart();

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
                            </tr>
                        `);
                        }
                    );

                    $("#filterModal").modal("hide");
                }
            },
            error: function (xhr, status, error) {
                console.error("Filter AJAX Error: " + status + error);
            },
        });
    });
});
