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
                console.log(response.already_clocked_in);
                console.log(response.already_clocked_out);

                if (
                    !response.already_clocked_in &&
                    !response.already_clocked_out
                ) {
                    // Kondisi ketika employee belum clock in dan belum clock out
                    $(".btn-clock-in").prop("disabled", false).text("Clock In");

                    $(".btn-clock-out")
                        .prop("disabled", true)
                        .text("Clock Out");
                } else if (
                    response.already_clocked_in &&
                    !response.already_clocked_out
                ) {
                    // Kondisi ketika employee sudah clock in dan belum clock out
                    $(".btn-clock-in").prop("disabled", true).text("Clock In");

                    $(".btn-clock-out")
                        .prop("disabled", false)
                        .text("Clock Out");
                } else {
                    // Kondisi ketika employee sudah clock in dan sudah clock out
                    $(".btn-clock-in").prop("disabled", true).text("Clock In");

                    $(".btn-clock-out")
                        .prop("disabled", true)
                        .text("Clock Out");
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

    // tampilkan status employee pada card
    function statusEmployee() {
        let employee_id = $("#attendance_employee_id").val();

        $.ajax({
            url: "/api/attendance/get-status/" + employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    console.log(response.attendanceStatus);
                    $("#text-status-attendance").text(
                        response.attendanceStatus ?? "-"
                    );
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

    statusEmployee();

    // ketika tombol clock in diklik
    $(document).on("click", ".btn-clock-in", () => {
        let dateText = $(".realtime-date").text();
        let clockIn = $(".realtime-clock").text();

        // cek status clock in employee
        let maxClock = "08:15:00";
        let status = clockIn <= maxClock ? "ontime" : "late";

        // Debug
        // console.log(formattedDate);
        console.log(clockIn);

        $.ajax({
            url: "/api/attendance/add-attendance",
            type: "POST",
            dataType: "json",
            data: {
                clock_in: clockIn,
                clock_in_status: status,
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
                    statusEmployee();
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

    // clock out, update kolom clock_out dan clock_out_status pada database
    // ambil waktu saat ini ketika tombol diklik jquery
    $(document).on("click", ".btn-clock-out", () => {
        let employee_id = $("#attendance_employee_id").val();
        let now = new Date();
        let hours = String(now.getHours()).padStart(2, "0");
        let minutes = String(now.getMinutes()).padStart(2, "0");
        let seconds = String(now.getSeconds()).padStart(2, "0");
        let clock_out = `${hours}:${minutes}:${seconds}`;
        let clock_out_status = "";

        // Cek apakah employee clock out sebelum atau sesudah waktu yang ditentukan
        /*
        - ontime : employee clock out pada pukul 16:00
        - early : employee clock out sebelum pukul 16:00 (mungkin bisa dibuat agar tombolnya hanya bisa diklik ketika pukul 16:00)
        - late : ketika employee clock out lebih dari jam 17:00
        - no_clock_out : ketika employee tidak clock out sampai hari berganti
        */

        console.log("waktu clockout" + clock_out);
        if (clock_out >= "16:00:00" && clock_out <= "17:00:00") {
            clock_out_status = "ontime";
            console.log("ontime" + clock_out);
        } else if (clock_out > "17:00:00") {
            clock_out_status = "late";
            console.log("late" + clock_out);
        } else if (clock_out >= "00:00:00") {
            clock_out_status = "absent";
            console.log("absent" + clock_out);
        } else {
            clock_out_status = "early";
            console.log("early" + clock_out);
        }

        $.ajax({
            url: "/api/attendance/clock-out/" + employee_id,
            type: "PUT",
            dataType: "json",
            data: {
                clock_out: clock_out,
                clock_out_status: clock_out_status,
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
                    statusEmployee();
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
