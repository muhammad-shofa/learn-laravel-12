import Swal from "sweetalert2";

$(document).ready(function () {
    // function to get all attendances data
    function loadAttendancesData() {
        $("#attendanceTableData").DataTable({
            destroy: true,
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/attendance/get-attendances",
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
                    data: "employee",
                    render: function (data) {
                        return data ? data.employee_code : "EMP Not Found";
                    },
                },
                {
                    data: "employee",
                    render: function (data) {
                        return data ? data.full_name : "-";
                    },
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
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-attendance_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
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

        $.ajax({
            url: "/api/attendance/get-holidays",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#content-max-holidays").text(
                        "Max Weekly Holidays : " + response.max_weekly_holidays
                    );
                    $("#content-days").text("Days : " + response.days);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    }

    // check weekly holiday
    // function checkWeeklyHoliday() {
    //     $.ajax({
    //         url: "/api/attendance/check-weekly-holiday",
    //         type: "GET",
    //         dataType: "json",
    //         success: function (response) {
    //             if (response.success && response.holiday) {
    //                 // Disable tombol jika hari ini libur
    //                 $("#btn-clock-in").attr("disabled", true);
    //                 $("#btn-clock-out").attr("disabled", true);

    //                 $("#alert-dayoff").html(
    //                     `<div class="alert alert-info">
    //                         Today is <strong>${response.day}</strong>, it's a scheduled day off.
    //                     </div>`
    //                 );
    //             }
    //         },
    //         error: function () {
    //             console.error("Failed to check weekly holiday.");
    //         },
    //     });
    // }

    loadAttendancesData();

    // ketika tombol edit diklik
    $(document).on("click", ".btn-edit", function () {
        let attendance_id = $(this).data("attendance_id");
        $.ajax({
            url: "/api/attendance/get-attendance/" + attendance_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#attendance_id").val(response.data.id);
                    $("#edit_clock_in").val(response.data.clock_in);
                    $("#edit_clock_out").val(response.data.clock_out);
                    $("#edit_clock_in_status").val(
                        response.data.clock_in_status
                    );
                    $("#edit_clock_out_status").val(
                        response.data.clock_out_status
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol save edit diklik
    $(document).on("click", ".save-edit", function () {
        let attendance_id = $("#attendance_id").val();
        let edit_clock_in = $("#edit_clock_in").val();
        let edit_clock_out = $("#edit_clock_out").val();
        let edit_clock_in_status = $("#edit_clock_in_status").val();
        let edit_clock_out_status = $("#edit_clock_out_status").val();

        // console.log("btn edit clicked");
        // console.log("clock in " + edit_clock_in);
        // console.log("clock out " + edit_clock_out);
        // console.log("clock in status " + edit_clock_in_status);
        // console.log("clock out status " + edit_clock_out_status);

        $.ajax({
            url: "/api/attendance/update-attendance/" + attendance_id,
            type: "PUT",
            dataType: "json",
            data: {
                clock_in: edit_clock_in,
                clock_out: edit_clock_out,
                clock_in_status: edit_clock_in_status,
                clock_out_status: edit_clock_out_status,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#editModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Attendance data has been updated successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadAttendancesData();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

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
            $("#btn-clock-in").attr("disabled", true);
        }
        // else {
        //     $('#btn-clock-in').attr('disabled', true);
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
    // v1
    // function checkDisableClockIO() {
    //     let attendance_employee_id = $("#attendance_employee_id").val();
    //     $.ajax({
    //         url:
    //             "/api/attendance/get-clock-io-attendance/" +
    //             attendance_employee_id,
    //         type: "GET",
    //         dataType: "json",
    //         success: (response) => {
    //             console.log(response.already_clocked_in);
    //             console.log(response.already_clocked_out);

    //             if (
    //                 !response.already_clocked_in &&
    //                 !response.already_clocked_out
    //             ) {
    //                 // Kondisi ketika employee belum clock in dan belum clock out
    //                 $("#btn-clock-in")
    //                     .prop("disabled", false)
    //                     .text("Clock In")
    //                     .addClass("btn-clock-in");

    //                 $("#btn-clock-out")
    //                     .prop("disabled", true)
    //                     .text("Clock Out")
    //                     .addClass("btn-nonactive");
    //             } else if (
    //                 response.already_clocked_in &&
    //                 !response.already_clocked_out
    //             ) {
    //                 // Kondisi ketika employee sudah clock in dan belum clock out
    //                 $("#btn-clock-in")
    //                     .prop("disabled", true)
    //                     .text("Clock In")
    //                     .removeClass("btn-clock-in")
    //                     .addClass("btn-nonactive");

    //                 $("#btn-clock-out")
    //                     .prop("disabled", false)
    //                     .text("Clock Out")
    //                     .removeClass("btn-nonactive")
    //                     .addClass("btn-clock-out");
    //             } else {
    //                 // Kondisi ketika employee sudah clock in dan sudah clock out
    //                 $("#btn-clock-in")
    //                     .prop("disabled", true)
    //                     .text("Clock In")
    //                     .removeClass("btn-clock-in")
    //                     .addClass("btn-nonactive");

    //                 $("#btn-clock-out")
    //                     .prop("disabled", true)
    //                     .text("Clock Out")
    //                     .removeClass("btn-clock-out")
    //                     .addClass("btn-nonactive");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("AJAX Error: " + status + error);
    //             Swal.fire({
    //                 title: "Error!",
    //                 text: "Your attendance failed to be recorded.",
    //                 icon: "Error",
    //                 confirmButtonText: "Oke",
    //             });
    //         },
    //     });
    // }

    // v2
    function checkDisableClockIO() {
        let attendance_employee_id = $("#attendance_employee_id").val();

        // Langkah 1: Ambil status Clock In/Out
        $.ajax({
            url:
                "/api/attendance/get-clock-io-attendance/" +
                attendance_employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                // Langkah 2: Cek apakah hari ini hari libur mingguan
                $.ajax({
                    url: "/api/attendance/check-weekly-holiday",
                    type: "GET",
                    dataType: "json",
                    success: function (holidayRes) {
                        const isDayOff =
                            holidayRes.success && holidayRes.holiday;

                        // Jika hari libur, disable semua tombol
                        if (isDayOff) {
                            $("#btn-clock-in")
                                .prop("disabled", true)
                                .text("Clock In")
                                .removeClass("btn-clock-in")
                                .addClass("btn-nonactive");

                            $("#btn-clock-out")
                                .prop("disabled", true)
                                .text("Clock Out")
                                .removeClass("btn-clock-out")
                                .addClass("btn-nonactive");

                            // Tampilkan pesan info kalau hari ini libur
                            $("#alert-dayoff").html(`
                                <div class="alert alert-info mt-2">
                                    Today is <strong>${holidayRes.day}</strong>, it's your weekly holiday.
                                </div>
                            `);
                            return; // berhenti di sini jika libur
                        }

                        // Jika tidak libur, lanjutkan logika seperti biasa
                        if (
                            !response.already_clocked_in &&
                            !response.already_clocked_out
                        ) {
                            $("#btn-clock-in")
                                .prop("disabled", false)
                                .text("Clock In")
                                .addClass("btn-clock-in");

                            $("#btn-clock-out")
                                .prop("disabled", true)
                                .text("Clock Out")
                                .addClass("btn-nonactive");
                        } else if (
                            response.already_clocked_in &&
                            !response.already_clocked_out
                        ) {
                            $("#btn-clock-in")
                                .prop("disabled", true)
                                .text("Clock In")
                                .removeClass("btn-clock-in")
                                .addClass("btn-nonactive");

                            $("#btn-clock-out")
                                .prop("disabled", false)
                                .text("Clock Out")
                                .removeClass("btn-nonactive")
                                .addClass("btn-clock-out");
                        } else {
                            $("#btn-clock-in")
                                .prop("disabled", true)
                                .text("Clock In")
                                .removeClass("btn-clock-in")
                                .addClass("btn-nonactive");

                            $("#btn-clock-out")
                                .prop("disabled", true)
                                .text("Clock Out")
                                .removeClass("btn-clock-out")
                                .addClass("btn-nonactive");
                        }

                        // Kosongkan alert jika bukan hari libur
                        $("#alert-dayoff").html("");
                    },
                    error: function () {
                        console.error("Failed to check weekly holiday.");
                    },
                });
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
                    console.log("clock in status :" + response.clockInStatus);
                    $("#text-clock-in-status-attendance").text(
                        response.clockInStatus ?? "-"
                    );
                    $("#text-clock-out-status-attendance").text(
                        response.clockOutStatus &&
                            response.clockOutStatus != "no_clock_out"
                            ? response.clockOutStatus
                            : "-"
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
    $(document).on("click", "#btn-clock-in", () => {
        let now = new Date();
        let hours = String(now.getHours()).padStart(2, "0");
        let minutes = String(now.getMinutes()).padStart(2, "0");
        let seconds = String(now.getSeconds()).padStart(2, "0");
        let clock_in = `${hours}:${minutes}:${seconds}`;
        let clock_in_status = "";

        // cek status clock in employee
        // let maxClockIn = "08:15:00";
        // let clock_in_status = clockIn <= maxClock ? "ontime" : "late";

        // Debug
        // console.log(formattedDate);
        console.log(clock_in);
        if (clock_in >= "07:45:00" && clock_in <= "08:15:00") {
            console.log("ontime");
            clock_in_status = "ontime";
        } else if (clock_in > "08:15:00" && clock_in < "16:00:00") {
            console.log("late");
            clock_in_status = "late";
        } else {
            console.log("absent");
            clock_in_status = "absent";
        }

        $.ajax({
            url: "/api/attendance/add-attendance",
            type: "POST",
            dataType: "json",
            data: {
                clock_in: clock_in,
                clock_in_status: clock_in_status,
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
    $(document).on("click", "#btn-clock-out", () => {
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
        if (!clock_out) {
            clock_out_status = "no_clock_out";
            console.log("no_clock_out");
        } else if (clock_out >= "16:00:00" && clock_out <= "17:00:00") {
            clock_out_status = "ontime";
            console.log("ontime" + clock_out);
        } else if (clock_out > "17:00:00") {
            clock_out_status = "late";
            console.log("late" + clock_out);
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

    // settings weekly holidays
    $(".save-weekly-setting").on("click", function (e) {
        e.preventDefault();

        let max = parseInt(
            $("input[name='max_holidays_per_week']:checked").val()
        );
        let selectedDays = $("input[name='days[]']:checked").length;

        if (selectedDays > max) {
            Swal.fire({
                icon: "warning",
                title: "Too many holidays",
                text: `Make sure the day you choose does not exceed the max holiday.`,
                confirmButtonColor: "#3085d6",
            });
            return;
        }

        let formData = $("#formHolidaySetting").serialize();

        $.ajax({
            url: "/api/attendance/weekly-holiday-setting",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    loadAttendancesData();
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Weekly holiday setting saved.",
                        timer: 1500,
                        confirmButtonText: "Oke",
                    });
                    $("#modalHolidaySetting").modal("hide");
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Fail!",
                    text: "Failed to update weekly holiday setting.",
                });
            },
        });
    });

    // ketika tombol export PDF attendances diklik
    $(document).on("click", "#btnExportAttendance", function () {
        $.ajax({
            url: "/api/attendance/export-pdf",
            type: "GET",
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
                        ?.split("filename=")[1] || `attendance_report.pdf`;

                link.download = filename.replaceAll('"', "");
                link.click();
            },
            error: function (xhr, status, error) {
                alert(
                    "Failed to download attendance report. Please try again."
                );
                console.error(error);
            },
        });
    });
});
