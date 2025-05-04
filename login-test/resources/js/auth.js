$(document).ready(function () {
    // tangani ketika user mengklik tombol login
    $("#loginForm").on("submit", (e) => {
        e.preventDefault(); // mencegah form submit secara default
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
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    // jika login berhasil, redirect ke halaman dashboard
                    window.location.href = "/dashboard";
                } else {
                    // jika login gagal, tampilkan pesan error
                    $("#errorMessage").text(response.message);
                }
            },
            error: function (xhr, status, error) {
                // jika terjadi error, tampilkan pesan error
                $("#errorMessage").text(
                    "Terjadi kesalahan. Silakan coba lagi."
                );
            },
        });
    });
});
