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
                { data: "request_date" },
                { data: "start_date" },
                { data: "end_date" },
                { data: "reason" },
                {
                    data: 'status',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            // Tentukan warna badge berdasar isi status
                            let badgeClass = 'text-bg-secondary';   // default abu-abu
                            if (data === 'ontime') badgeClass  = 'text-bg-success';
                            if (data === 'late')   badgeClass  = 'text-bg-danger';
                            if (data === 'pending')badgeClass  = 'text-bg-warning';
        
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        // untuk sorting / searching gunakan nilai mentah
                        return data;
                    }
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
    }

    // tampilkan data history time off request untuk employee tertentu
    function loadHistoryTimeOffRequestData() {
        let employee_id = $("#time_off_employee_id_hidden").val();

        $.ajax({
            url: "/api/time-off/get-time-off-request/" + employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let historyTimeOffRequestTable = $(
                        "#historyTimeOffRequestData tbody"
                    );
                    let no = 0;
                    historyTimeOffRequestTable.empty();
                    $.each(response.data, (index, time_off) => {
                        no++;

                        // Pilih warna badge berdasarkan status
                        let statusBadge = "-";
                        if (time_off.status === "approved") {
                            statusBadge = `<span class="badge bg-success">Approved</span>`;
                        } else if (time_off.status === "pending") {
                            statusBadge = `<span class="badge bg-warning text-dark">Pending</span>`;
                        } else if (time_off.status === "rejected") {
                            statusBadge = `<span class="badge bg-danger">Rejected</span>`;
                        }

                        historyTimeOffRequestTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${time_off.request_date}</td>
                                <td>${time_off.start_date}</td>
                                <td>${time_off.end_date}</td>
                                <td>${statusBadge}</td>
                            </tr>
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

    loadTimeOffRequestsData();
    loadHistoryTimeOffRequestData();

    $(".submit-time-off-request").on("click", () => {
        let employee_id = $("#time_off_employee_id_hidden").val();
        let start_date = $("#start_date").val();
        let end_date = $("#end_date").val();
        let reason = $("#reason").val();

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
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
