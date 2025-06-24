import Swal from "sweetalert2";

$(document).ready(function () {
    let calendarEl = document.getElementById("attendanceCalendar");
    // let calendarEl = $("#attendanceCalendar");
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: 600,
        dateClick: function (info) {
            let dateClicked = info.dateStr;

            $("#triggerOffcanvasAttendance").click();

            // Set judul sementara dan loading
            $("#offcanvasAttendanceLabel").text(
                `Attendance Date ${dateClicked}`
            );
            $("#attendanceDetailContent").html(
                `<p>Loading attendances data...</p>`
            );

            // Ambil data kehadiran dari API
            $.ajax({
                url: `/api/attendance/by-date/${dateClicked}`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success && response.attendances.length > 0) {
                        var html = "<ul class='list-group'>";
                        response.attendances.forEach(function (item) {
                            // Logic badge clock_in_status
                            let clockInBadge = "text-bg-secondary";
                            if (item.clock_in_status === "ontime")
                                clockInBadge = "text-bg-success";
                            if (
                                item.clock_in_status === "late" ||
                                item.clock_in_status === "absent"
                            )
                                clockInBadge = "text-bg-danger";
                            if (item.clock_in_status === "leave")
                                clockInBadge = "text-bg-warning";

                            // Logic badge clock_out_status
                            let clockOutBadge = "text-bg-secondary";
                            if (item.clock_out_status === "ontime")
                                clockOutBadge = "text-bg-success";
                            if (item.clock_out_status === "early")
                                clockOutBadge = "text-bg-warning";
                            if (
                                item.clock_out_status === "late" ||
                                item.clock_out_status === "no_clock_out"
                            )
                                clockOutBadge = "text-bg-danger";

                            html += `
                                <li class="list-group-item">
                                    <strong>${
                                        item.employee.full_name
                                    }</strong><br>
                                    Clock In: ${item.clock_in || "-"}<br>
                                    Clock Out: ${item.clock_out || "-"}<br>
                                    Clock In Status: <span class="badge ${clockInBadge}">${
                                item.clock_in_status
                            }</span><br>
                                    Clock Out Status: <span class="badge ${clockOutBadge}">${
                                item.clock_out_status
                            }</span>
                                </li>
                            `;
                        });
                        html += "</ul>";
                        $("#attendanceDetailContent").html(html);
                    } else {
                        $("#attendanceDetailContent").html(
                            `<p>No attendance data for this date.</p>`
                        );
                    }
                },
                error: function () {
                    $("#attendanceDetailContent").html(
                        `<p>An error occurred while retrieving the data.</p>`
                    );
                },
            });
        },
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url: "/api/attendance/summary",
                type: "GET",
                dataType: "json",
                data: {
                    start: info.startStr,
                    end: info.endStr,
                },
                success: function (response) {
                    if (response.success) {
                        let events = [];
                        for (let date in response.data) {
                            events.push({
                                title: response.data[date] + " Attendances",
                                start: date,
                                backgroundColor: "#708A58",
                            });
                        }
                        successCallback(events);
                    }
                },
                error: function () {
                    failureCallback();
                },
            });
        },
    });

    // render attendance calendar
    calendar.render();

    // reset filter
    $(document).on("click", ".btn-reset-filter", () => {
        loadReportSalarySummary();
        loadReportTimeOffSummary();
    });

    let filterTarget = null;

    $(document).on("click", ".btn-show-filter", function () {
        filterTarget = $(this).data("target");

        const now = new Date();
        $("#filter_month").val(now.getMonth() + 1);
        $("#filter_year").val(now.getFullYear());

        $("#filterModal").modal("show");
    });

    // apply filter
    $(document).on("click", ".btn-apply-filter", () => {
        const month = $("#filter_month").val();
        const year = $("#filter_year").val();

        if (!filterTarget) {
            alert("Unknown filter target.");
            return;
        }

        let url = "";
        let successHandler;

        if (filterTarget === "salary") {
            url = "/api/report/filter-salary-data";
            successHandler = (response) => {
                $("#currentMonth").text(response.current_month);
                $("#currentYear").text(response.current_year);
                salary_paid_content_numeric.set(response.total_paid);
                salary_deduction_content_numeric.set(response.total_deduction);
                salary_bonus_content_numeric.set(response.total_bonus);
            };
        } else if (filterTarget === "timeoff") {
            url = "/api/report/filter-time-off-data";
            successHandler = (response) => {
                $("#currentMonthTimeOff").text(response.current_month);
                $("#currentYearTimeOff").text(response.current_year);
                const options = {
                    series: [
                        {
                            name: "Total Request",
                            data: response.total_requests,
                        },
                        { name: "Approved", data: response.approved_requests },
                        { name: "Rejected", data: response.rejected_requests },
                        { name: "Pending", data: response.pending_requests },
                    ],
                    chart: {
                        height: 350,
                        type: "bar",
                    },
                    xaxis: {
                        categories: [
                            response.current_month +
                                " " +
                                response.current_year,
                        ],
                    },
                    plotOptions: {
                        bar: { columnWidth: "55%" },
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2 },
                    fill: { opacity: 1 },
                };

                if (timeOffChartInstance) {
                    timeOffChartInstance.destroy();
                }

                timeOffChartInstance = new ApexCharts(
                    document.querySelector("#timeOffChart"),
                    options
                );
                timeOffChartInstance.render();
            };
        }

        $.ajax({
            url: url,
            type: "GET",
            data: { month, year },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#filterModal").modal("hide");
                    successHandler(response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Filter AJAX Error:", error);
            },
        });
    });

    // ketika tombol download pdf attendances diklik
    $(document).on("click", "#btnDownloadAttendanceReport", function () {
        let currentDate = calendar.getDate();
        let month = currentDate.getMonth() + 1;
        let year = currentDate.getFullYear();

        $.ajax({
            url: "/api/report/attendances/pdf",
            method: "POST",
            data: {
                month: month,
                year: year,
            },
            xhrFields: {
                responseType: "blob",
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response, status, xhr) {
                // Buat blob dan unduh file
                let blob = new Blob([response], { type: "application/pdf" });
                let downloadUrl = URL.createObjectURL(blob);
                let a = document.createElement("a");

                // Nama file
                a.href = downloadUrl;
                a.download = `attendances-report-${month}-${year}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            },
            error: function (xhr, status, error) {
                alert("Failed to download PDF, please try again.");
                console.error(error);
            },
        });
    });

    const monthMap = {
        January: 1,
        February: 2,
        March: 3,
        April: 4,
        May: 5,
        June: 6,
        July: 7,
        August: 8,
        September: 9,
        October: 10,
        November: 11,
        December: 12,
    };

    // ketika tombol download pdf time off request diklik
    $(document).on("click", "#btnDownloadTimeOffReport", function () {
        const monthName = $("#currentMonthTimeOff").text().trim();
        const month = monthMap[monthName];
        const year = new Date().getFullYear();

        $.ajax({
            url: "/api/report/time-off-request/pdf",
            type: "POST",
            data: { month, year },
            xhrFields: {
                responseType: "blob",
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response, status, xhr) {
                const blob = new Blob([response], { type: "application/pdf" });
                const link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);

                const filename =
                    xhr
                        .getResponseHeader("Content-Disposition")
                        ?.split("filename=")[1] ||
                    `time_off_report_${month}_${year}.pdf`;

                link.download = filename.replaceAll('"', "");
                link.click();
            },
            error: function (xhr, status, error) {
                alert("Failed to download monthly report. Please try again.");
                console.error(error);
            },
        });
    });

    // ketika tombol download pdf salary diklik
    $(document).on("click", "#btnDownloadSalaryReport", function () {
        const monthName = $("#currentMonth").text().trim();
        const month = monthMap[monthName];
        const year = new Date().getFullYear();

        $.ajax({
            url: "/api/report/salaries/pdf",
            type: "POST",
            data: { month, year },
            xhrFields: {
                responseType: "blob",
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response, status, xhr) {
                const blob = new Blob([response], { type: "application/pdf" });
                const link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);

                const filename =
                    xhr
                        .getResponseHeader("Content-Disposition")
                        ?.split("filename=")[1] ||
                    `salary_report_${month}_${year}.pdf`;

                link.download = filename.replaceAll('"', "");
                link.click();
            },
            error: function (xhr, status, error) {
                alert("Failed to download monthly report. Please try again.");
                console.error(error);
            },
        });
    });

    // auto numeric library untuk inputan
    const salary_paid_content_numeric = new AutoNumeric(
        "#salary-paid-content",
        {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        }
    );

    // auto numeric library untuk inputan
    const salary_deduction_content_numeric = new AutoNumeric(
        "#salary-deduction-content",
        {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        }
    );

    // auto numeric library untuk inputan
    const salary_bonus_content_numeric = new AutoNumeric(
        "#salary-bonus-content",
        {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            modifyValueOnWheel: false,
        }
    );

    // ketika tombol time off-tab diklik
    $(document).on("click", "#timeoff-tab", function () {
        loadReportTimeOffSummary();
    });

    let timeOffChartInstance = null;

    // v2
    function loadReportTimeOffSummary() {
        $.ajax({
            url: "/api/time-off/summary",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#currentMonthTimeOff").text(response.current_month);
                    $("#currentYearTimeOff").text(response.current_year);
                    const categories = [
                        response.current_month + " " + response.current_year,
                    ];

                    const options = {
                        series: [
                            {
                                name: "Total Request",
                                data: response.total_requests,
                            },
                            {
                                name: "Approved",
                                data: response.approved_requests,
                            },
                            {
                                name: "Rejected",
                                data: response.rejected_requests,
                            },
                            {
                                name: "Pending",
                                data: response.pending_requests,
                            },
                        ],
                        chart: {
                            height: 350,
                            type: "bar",
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: "55%",
                                endingShape: "rounded",
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ["transparent"],
                        },
                        xaxis: {
                            categories: categories,
                        },
                        yaxis: {
                            title: {
                                text: "Total Requests",
                            },
                        },
                        fill: {
                            opacity: 1,
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " requests";
                                },
                            },
                        },
                    };

                    // Hapus chart lama jika ada
                    if (timeOffChartInstance) {
                        timeOffChartInstance.destroy();
                    }

                    // Buat chart baru
                    timeOffChartInstance = new ApexCharts(
                        document.querySelector("#timeOffChart"),
                        options
                    );
                    timeOffChartInstance.render();
                }
            },
            error: function (xhr, status, error) {
                alert("Failed to load summary chart.");
                console.error(error);
            },
        });
    }

    $(document).on("click", "#salary-tab", loadReportSalarySummary());

    function loadReportSalarySummary() {
        $.ajax({
            url: "/api/salary/summary",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#currentMonth").text(response.current_month);
                    $("#currentYear").text(response.current_year);
                    salary_paid_content_numeric.set(response.salary_paid ?? 0);
                    salary_deduction_content_numeric.set(
                        response.salary_deduction ?? 0
                    );
                    salary_bonus_content_numeric.set(
                        response.salary_bonus ?? 0
                    );
                }
            },
            error: function (xhr, status, error) {
                alert("Failed to download PDF, please try again.");
                console.error(error);
            },
        });
    }
});
