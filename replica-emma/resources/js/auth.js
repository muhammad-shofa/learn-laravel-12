import Swal from "sweetalert2";

$(document).ready(function () {
    // Handle the form submission
    $("#loginForm").on("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the form data
        var formData = $(this).serialize();

        // Send the AJAX request
        $.ajax({
            url: "/api/auth/login",
            type: "POST",
            dataType: "json",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    console.log("Login successful");
                    // console.log(response.datauser);
                    window.location.href = "/dashboard";
                } else {
                    Swal.fire({
                        title: "Login Failed!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "Try again",
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
