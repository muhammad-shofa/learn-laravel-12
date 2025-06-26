import Swal from "sweetalert2";

$(document).ready(function () {
    // load all time off request data
    function loadTimeOffRequestsData() {
        $("#timeOffTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/time-off/get-time-off-requests",
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
                { data: "request_date" },
                { data: "start_date" },
                { data: "end_date" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-detail-reason btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailReasonModal" data-time_off_id="${row.id}">
                                <i class="fa-solid fa-eye"></i>
                            </button>`;
                    },
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        if (type === "display") {
                            // Tentukan warna badge berdasar isi status
                            let badgeClass = "text-bg-secondary"; // default abu-abu
                            if (data === "approved")
                                badgeClass = "text-bg-success";
                            if (data === "rejected")
                                badgeClass = "text-bg-danger";
                            if (data === "pending")
                                badgeClass = "text-bg-warning";

                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        // untuk sorting / searching gunakan nilai mentah
                        return data;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        // jika status bukan pending, sembunyikan tombol
                        let isdisable =
                            row.status !== "pending" ? "d-none" : "";

                        if (isdisable == "d-none") {
                            return `<p>-</p>`;
                        }

                        return `
                            <button class="btn-approve btn btn-success ${isdisable}" data-time_off_id="${row.id}">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button class="btn-reject btn btn-danger ${isdisable}" data-time_off_id="${row.id}">
                             <i class="fa-solid fa-xmark"></i>
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

    // tampilkan data history time off request untuk employee tertentu
    function loadHistoryTimeOffRequestData() {
        let employee_id = $("#time_off_employee_id_hidden").val();
        $("#historyTimeOffRequestData").DataTable({
            destroy: true,
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url:
                    "/api/time-off/get-time-off-request-employee-id/" +
                    employee_id,
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
                { data: "request_date" },
                { data: "start_date" },
                { data: "end_date" },
                {
                    data: "status",
                    render: function (data, type, row) {
                        if (type === "display") {
                            // Tentukan warna badge berdasar isi status
                            let badgeClass = "text-bg-secondary"; // default abu-abu
                            if (data === "approved")
                                badgeClass = "text-bg-success";
                            if (data === "rejected")
                                badgeClass = "text-bg-danger";
                            if (data === "pending")
                                badgeClass = "text-bg-warning";

                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        // untuk sorting / searching gunakan nilai mentah
                        return data;
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

    loadTimeOffRequestsData();
    loadHistoryTimeOffRequestData();

    // ketika tombol detail di klik
    $(document).on("click", ".btn-detail-reason", function () {
        let time_off_id = $(this).data("time_off_id");
        $.ajax({
            url: "/api/time-off/get-time-off-request/" + time_off_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    // let time_off = response.data;
                    $("#time-off-reason-field").text(response.data.reason);
                } else {
                    console.log(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol approve di klik
    $(document).on("click", ".btn-approve", function () {
        let time_off_id = $(this).data("time_off_id");
        Swal.fire({
            title: "Comfirmation",
            text: "Are you sure you want to approve this leave request?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/time-off/approve-time-off",
                    type: "PUT",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        time_off_id: time_off_id,
                    },
                    success: (response) => {
                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "Oke",
                            });
                            loadTimeOffRequestsData();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + error);
                    },
                });
            }
        });
    });

    // ketika tombol reject di klik
    $(document).on("click", ".btn-reject", function () {
        let time_off_id = $(this).data("time_off_id");
        Swal.fire({
            title: "Comfirmation",
            text: "Are you sure you want to reject this leave request?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/time-off/reject-time-off",
                    type: "PUT",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        time_off_id: time_off_id,
                    },
                    success: (response) => {
                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "Oke",
                            });
                            loadTimeOffRequestsData();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + error);
                    },
                });
            }
        });
    });

    // submit form untuk menambah time off request dari employee
    $(".submit-time-off-request").on("click", () => {
        let employee_id = $("#time_off_employee_id_hidden").val();
        let start_date = $("#start_date").val();
        let end_date = $("#end_date").val();
        let reason = $("#reason").val();

        // end date tidak boleh lebih kecil dari start date
        if (new Date(end_date) < new Date(start_date)) {
            Swal.fire({
                title: "Failed!",
                text: "End date cannot be less than start date",
                icon: "error",
                confirmButtonText: "Oke",
            });
            return;
        }

        // console.log(id);
        // console.log(start_date);
        // console.log(end_date);
        // console.log(reason);

        $.ajax({
            url: "/api/time-off/new-time-off",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employee_id,
                start_date: start_date,
                end_date: end_date,
                reason: reason,
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
                    loadHistoryTimeOffRequestData();
                    $("#addTimeOffForm")[0].reset();
                } else {
                    console.log(response.message);
                    Swal.fire({
                        title: "Failed!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "Oke",
                    });
                    loadHistoryTimeOffRequestData();
                    $("#addTimeOffForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol export PDF di klik
    $(document).on("click", "#btnExportTimeOff", function () {
        $.ajax({
            url: "/api/time-off/export-pdf",
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
                        ?.split("filename=")[1] || `time_off_report.pdf`;

                link.download = filename.replaceAll('"', "");
                link.click();
            },
            error: function (xhr, status, error) {
                alert("Failed to download time off report. Please try again.");
                console.error(error);
            },
        });
    });
});
