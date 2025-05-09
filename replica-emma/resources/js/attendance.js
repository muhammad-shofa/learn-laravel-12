import Swal from "sweetalert2";

$(document).ready(function () {
    // function to get all attendances data
    function loadAttendancesData() {
        $.ajax({
            url: "/api/attendance/get-attendances",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let attendacesTable = $("#attendanceTableData tbody");
                    let no = 0;
                    attendacesTable.empty();
                    $.each(response.data, (index, attendance) => {
                        no++;
                        attendacesTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${
                                    attendance.employee
                                        ? attendance.employee.employee_code
                                        : "EMP Not Found"
                                }</td>
                                <td>${attendance.date}</td>
                                <td>${attendance.clock_in ?? "-"}</td>
                                <td>${attendance.clock_out ?? "-"}</td>
                                <td>${attendance.status ?? "-"}</td>
                                <td>
                                    <button class="btn-edit btn btn-primary" data-attendance_id="${
                                        attendance.id
                                    }" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen"></i></button>
                                </td>
                            <tr>
                            `);
                    });
                } else {
                    console.log(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    }

    loadAttendancesData();

    // tampilkan date dan jam pada clock-in card
    function updateDateTime() {
        const now = new Date();

        // Format waktu: 2 digit
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        const seconds = String(now.getSeconds()).padStart(2, "0");

        // Tampilkan waktu (jam:menit:detik)
        const currentTime = `${hours}:${minutes}:${seconds}`;
        $(".realtime-clock").text(currentTime);

        // ketika jam 07:45 tombol clock_in baru bisa diklik
        let startClockInbtn = "07:45:00";
        let statusClockInBtn =
            currentTime <= startClockInbtn ? "nonactive" : "active";

        if (statusClockInBtn == "nonactive") {
            $(".btn-clock-in").attr("disabled", true);
        }
        // else {
        //     $('.btn-clock-in').attr('disabled', true);
        // }

        // Format date: Senin, 8 Mei 2025
        const day = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        const month = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        const dayName = day[now.getDay()];
        const date = now.getDate();
        const monthName = month[now.getMonth()];
        const year = now.getFullYear();

        const currentDate = `${dayName}, ${date} ${monthName} ${year}`;
        $(".realtime-date").text(currentDate);
    }

    // Perbarui setiap detik
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // disable clock in button
    function checkDisableClockIO() {
        let attendance_employee_id = $("#attendance_employee_id").val();
        $.ajax({
            url:
                "/api/attendance/get-clock-io-attendance/" +
                attendance_employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.already_clocked_in) {
                    $(".btn-clock-in")
                        .prop("disabled", true)
                        .text("Already Clock In");
                } else {
                    console.log(response.message);
                }

                if (
                    response.already_clocked_in &&
                    response.already_clocked_out
                ) {
                    $(".btn-clock-out")
                        .prop("disabled", true)
                        .text("Already Clock Out");
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Error!",
                    text: "Your attendance failed to be recorded.",
                    icon: "Error",
                    confirmButtonText: "Oke",
                });
            },
        });
    }

    checkDisableClockIO();

    $(document).on("click", ".btn-clock-in", () => {
        let dateText = $(".realtime-date").text();
        let clockIn = $(".realtime-clock").text();

        // Pisahkan date
        // let parts = dateText.split(",")[1].trim().split(" "); // hasil: ["8", "Mei", "2025"]

        // let day = parts[0];
        // let monthName = parts[1];
        // let year = parts[2];

        // Konversi nama month ke angka
        // const monthMap = {
        //     Januari: "01",
        //     Februari: "02",
        //     Maret: "03",
        //     April: "04",
        //     Mei: "05",
        //     Juni: "06",
        //     Juli: "07",
        //     Agustus: "08",
        //     September: "09",
        //     Oktober: "10",
        //     November: "11",
        //     Desember: "12",
        // };

        //
        // let month = monthMap[monthName];
        // let formattedDate = `${year}-${month}-${day.padStart(2, "0")}`;

        // cek status clock in employee
        let maxClock = "08:15:00";
        let status = clockIn <= maxClock ? "ontime" : "late";

        // Debug
        console.log(formattedDate);
        console.log(clockIn);

        $.ajax({
            url: "/api/attendance/add-attendance",
            type: "POST",
            dataType: "json",
            data: {
                clock_in: clockIn,
                status: status,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "Your attendance has been recorded.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    checkDisableClockIO();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Error!",
                    text: "Your attendance failed to be recorded.",
                    icon: "Error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });

    // clock out, update kolom clock_out pada database
    // ambil waktu saat ini ketika tombol diklik jquery
    $(document).on("click", ".btn-clock-out", () => {
        let employee_id = $("#attendance_employee_id").val();
        let now = new Date();
        let hours = String(now.getHours()).padStart(2, "0");
        let minutes = String(now.getMinutes()).padStart(2, "0");
        let seconds = String(now.getSeconds()).padStart(2, "0");
        let clock_out = `${hours}:${minutes}:${seconds}`;

        console.log(clock_out);
        $.ajax({
            url: "/api/attendance/clock-out/" + employee_id,
            type: "PUT",
            dataType: "json",
            data: {
                clock_out: clock_out,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "Clocked out successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    checkDisableClockIO();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Error!",
                    text: "Your attendance failed to be recorded.",
                    icon: "Error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });
});
