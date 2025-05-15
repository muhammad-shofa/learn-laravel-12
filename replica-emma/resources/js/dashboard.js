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

    loadDashboardData();
});
