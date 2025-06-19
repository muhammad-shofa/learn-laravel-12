import Swal from "sweetalert2";

$(document).ready(function () {
    // load

    // inisialisasi
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
                url: `/api/attendance/summary/${info.startStr}`,
                type: "GET",
                dataType: "json",
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
    calendar.render();

    // reset filter
    $(document).on("click", ".btn-reset-filter", () => {
        loadReportSalarySummary();
    });

    // apply filter
    $(document).on("click", ".btn-apply-filter", () => {
        const month = $("#filter_month").val();
        const year = $("#filter_year").val();
        // console.log(month);
        // console.log(year);

        $.ajax({
            url: "/api/report/filter-salary-data",
            type: "GET",
            data: { month: month, year: year },
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#filterModal").modal("hide");
                    $("#currentMonth").text(response.current_month);
                    salary_paid_content_numeric.set(response.total_paid);
                    salary_deduction_content_numeric.set(
                        response.total_deduction
                    );
                    salary_bonus_content_numeric.set(response.total_bonus);
                }
            },
            error: function (xhr, status, error) {
                console.error("Filter AJAX Error: " + status + error);
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

                // Nama file default
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

    // ketika tombol download pdf salary diklik
    // v3
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

                // Nama file dari header jika tersedia
                const filename =
                    xhr
                        .getResponseHeader("Content-Disposition")
                        ?.split("filename=")[1] ||
                    `salary_report_${month}_${year}.pdf`;

                link.download = filename.replaceAll('"', "");
                link.click();
            },
            error: function (xhr, status, error) {
                alert("Gagal mengunduh laporan. Silakan coba lagi.");
                console.error(error);
            },
        });
    });

    // v2
    // $(document).on("click", "#download-salary-pdf", function () {
    //     let month = $("#filter_month").val();
    //     let year = $("#filter_year").val();

    //     if (!month || !year) {
    //         alert("Please select both month and year.");
    //         return;
    //     }

    //     $.ajax({
    //         url: "/api/report/salaries/pdf",
    //         method: "POST",
    //         data: {
    //             month: month,
    //             year: year,
    //         },
    //         xhrFields: {
    //             responseType: "blob", // agar bisa mendownload file PDF
    //         },
    //         success: function (response, status, xhr) {
    //             // Buat link download dari blob
    //             const blob = new Blob([response], { type: "application/pdf" });
    //             const link = document.createElement("a");
    //             link.href = window.URL.createObjectURL(blob);
    //             link.download = `salary-report-${year}-${month}.pdf`;
    //             link.click();
    //         },
    //         error: function (xhr, status, error) {
    //             alert("Failed to download PDF, please try again.");
    //             console.error(error);
    //         },
    //     });
    // });

    // v1
    // $(document).on("click", "#btnDownloadSalaryReport", function () {
    //     let currentDate = calendar.getDate();
    //     let month = currentDate.getMonth() + 1;
    //     let year = currentDate.getFullYear();

    //     $.ajax({
    //         url: "/api/report/salaries/pdf",
    //         method: "POST",
    //         data: {
    //             month: month,
    //             year: year,
    //         },
    //         xhrFields: {
    //             responseType: "blob",
    //         },
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         success: function (response, status, xhr) {
    //             // Buat blob dan unduh file
    //             let blob = new Blob([response], { type: "application/pdf" });
    //             let downloadUrl = URL.createObjectURL(blob);
    //             let a = document.createElement("a");

    //             // Nama file default
    //             a.href = downloadUrl;
    //             a.download = `attendances-report-${month}-${year}.pdf`;
    //             document.body.appendChild(a);
    //             a.click();
    //             a.remove();
    //         },
    //         error: function (xhr, status, error) {
    //             alert("Failed to download PDF, please try again.");
    //             console.error(error);
    //         },
    //     });
    // });

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

    // ketika tombol salary-tab diklik
    $(document).on("click", "#salary-tab", loadReportSalarySummary());

    function loadReportSalarySummary() {
        $.ajax({
            url: "/api/salary/summary",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#currentMonth").text(response.current_month);
                    salary_paid_content_numeric.set(response.salary_paid);
                    salary_deduction_content_numeric.set(
                        response.salary_deduction
                    );
                    salary_bonus_content_numeric.set(response.salary_bonus);
                }
            },
            error: function (xhr, status, error) {
                alert("Failed to download PDF, please try again.");
                console.error(error);
            },
        });
    }
});
