$(document).ready(function () {
    function loadDashboardData() {
        $.ajax({
            url: "/api/dashboard/get-all-dashboard-data",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#employee_counts").text(response.employee_counts);
                    $("#late_counts").text(response.late_counts);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    }

    loadDashboardData();
});
