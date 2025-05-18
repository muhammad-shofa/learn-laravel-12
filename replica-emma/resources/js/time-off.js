import Swal from "sweetalert2";

$(document).ready(function () {
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
