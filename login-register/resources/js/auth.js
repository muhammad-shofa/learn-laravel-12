$(document).ready(function () {
    // tangani login form
    $("#btnLogin").on("click", () => {
        let username = $("#username").val();
        let password = $("#password").val();

        $.ajax({
            url: "/api/auth/login",
            type: "POST",
            dataType: "json",
            data: {
                username: username,
                password: password,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Menambahkan CSRF token
            },
            success: (response) => {
                if (response.success) {
                    // debug
                    // console.log("Login user data : ", response.user);
                    window.location.href = "/dashboard";
                } else {
                    alert("Login failed. Please check your credentials.");
                }
            },
            error: function (error) {
                console.error("Error during login:", error);
                alert("An error occurred. Please try again.");
            },
        });
    });
});
