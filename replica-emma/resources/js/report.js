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

            let offcanvasElement = $("#offcanvasAttendance");
            let bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
            bsOffcanvas.show();

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
    });
    calendar.render();

    // ketika tombol btnDownloadAttendanceReport diklik
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
});
