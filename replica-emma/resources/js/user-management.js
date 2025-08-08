import Swal from "sweetalert2";

$(document).ready(function () {
    // function to get all users data
    function loadUsersData() {
        $("#userTableData").DataTable({
            destroy: true,
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/user/get-users",
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
                    render: (data) =>
                        data ? data.employee_code : "EMP Not Found",
                },
                { data: "username" },
                { data: "try_login" },
                { data: "status_login" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-user_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-reset-password btn btn-warning text-white" data-user_id="${row.id}" data-bs-toggle="modal" data-bs-target="#resetpasswordModal">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button class="btn-delete btn btn-danger" data-user_id="${row.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    },
                },
            ],
            columnDefs: [
                {
                    targets: "_all",
                    className: "text-start align-middle",
                },
            ],
        });
    }

    // load users data
    loadUsersData();

    // ambil data employee code untuk ditampilkan pada select
    function selectEmployeeCode(select_id) {
        // let employeeCodeSelect = $(select_id);
        let employeeCodeSelect = $("#employee_code");

        // Inisialisasi Select2 langsung dengan AJAX
        employeeCodeSelect.select2({
            theme: "bootstrap4",
            placeholder: "-- Select Employee --",
            allowClear: true,
            width: "100%",
            dropdownParent: $("#addModal"),
            ajax: {
                url: "/api/employee/search",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // kata kunci pencarian
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map((employee) => ({
                            id: employee.id,
                            text: `${employee.employee_code} - ${employee.full_name}`,
                            disabled: employee.has_account == 1,
                        })),
                    };
                },
                cache: true,
            },
        });
    }

    // ketika tombol btn-add diklik maka
    // ambil data employee code untuk ditampilkan pada select
    $(document).on("click", ".btn-add", function () {
        $("#addModal").modal("show");
        selectEmployeeCode("#employee_code");
    });

    $(document).on("click", ".btn-edit", function () {
        $("#editModal").modal("show");
        selectEmployeeCode("#edit_employee_code");
    });

    // simpan data user
    $(document).on("click", ".save-add", function () {
        /* menggunakan employee_id karena employee_code adalah select option
         yang berisi employee_id dan full_name */
        let employee_id = $("#employee_code").val();
        let username = $("#username").val();
        let password = $("#password").val();
        let role = $("#role").val();

        // Validasi password
        let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!passwordRegex.test(password)) {
            Swal.fire({
                title: "Invalid Password",
                text: "Password must be at least 8 characters, containing uppercase, lowercase letters, numbers, and symbols.",
                icon: "warning",
                confirmButtonText: "Oke",
            });
            return;
        }

        $.ajax({
            url: "/api/user/add-user",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employee_id,
                username: username,
                password: password,
                role: role,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#addModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been added successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadUsersData();
                    $("#addUserForm")[0].reset();
                } else {
                    $("#addModal").modal("hide");
                    loadUsersData();
                    $("#addUserForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                let errorMessage = "Failed to add new data.";
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join(" ");
                }
                Swal.fire({
                    title: "Failed!",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "Oke",
                });
                console.error("AJAX Error: ", xhr.responseJSON);
            }
        });
    });

    // ketika tombol edit diklik
    $(document).on("click", ".btn-edit", function () {
        let user_id = $(this).data("user_id");
        $.ajax({
            url: "/api/user/get-user/" + user_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#edit_user_id").val(response.data.id);
                    $("#edit_employee_code").val(
                        response.data.employee ? response.data.employee.id : ""
                    );
                    $("#edit_username").val(response.data.username);
                    $("#edit_role").val(response.data.role);
                    $("#edit_try_login").val(response.data.try_login);
                    $("#edit_status_login").val(response.data.status_login);
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    // ketika tombol save edit diklik
    $(document).on("click", ".save-edit", function () {
        let user_id = $("#edit_user_id").val();
        let employee_id = $("#edit_employee_code").val();
        let username = $("#edit_username").val();
        let role = $("#edit_role").val();
        let try_login = $("#edit_try_login").val();
        let status_login = $("#edit_status_login").val();

        $.ajax({
            url: "/api/user/update-user/" + user_id,
            type: "PUT",
            dataType: "json",
            data: {
                employee_id: employee_id,
                username: username,
                role: role,
                try_login: try_login,
                status_login: status_login,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#editModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been updated successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadUsersData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Failed!",
                    text: "Failed to update data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });

    // ketika tombol reset password diklik
    $(document).on("click", ".btn-reset-password", function () {
        let user_id = $(this).data("user_id");
        // $("#resetpasswordModal").modal("show");
        $("#reset_user_id").val(user_id);
    });

    // ketika show password diklik
    $(document).on("click", "#show-new-password", function () {
        let newPasswordInput = $("#new_password");
        if (newPasswordInput.attr("type") === "password") {
            newPasswordInput.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            newPasswordInput.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye"></i>');
        }
    });

    $(document).on("click", "#show-confirm-password", function () {
        let confirmPasswordInput = $("#confirm_password");
        if (confirmPasswordInput.attr("type") === "password") {
            confirmPasswordInput.attr("type", "text");
            $(this).html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            confirmPasswordInput.attr("type", "password");
            $(this).html('<i class="fa-solid fa-eye"></i>');
        }
    });

    // ketika tombol konfirmasi reset password diklik
$(document).on("click", ".save-new-password", function () {
    let user_id = $("#reset_user_id").val();
    let new_password = $("#new_password").val().replace(/\s+/g, ""); // hapus semua spasi
    let confirm_password = $("#confirm_password").val().replace(/\s+/g, ""); // hapus semua spasi

    // update input field untuk memastikan user juga lihat hasilnya
    $("#new_password").val(new_password);
    $("#confirm_password").val(confirm_password);

    // Regex untuk validasi kompleksitas password
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]{6,}$/;

    if (!new_password) {
        Swal.fire({
            title: "Error!",
            text: "Password cannot be empty.",
            icon: "error",
            confirmButtonText: "Okay",
        });
        return;
    }

    if (new_password.length < 6) {
        Swal.fire({
            title: "Error!",
            text: "Password must be at least 6 characters long.",
            icon: "error",
            confirmButtonText: "Okay",
        });
        return;
    }

    if (!passwordRegex.test(new_password)) {
        Swal.fire({
            title: "Error!",
            html: "Password must be at least 6 characters long and include uppercase and lowercase letters, a number, a special character, and no spaces.",
            icon: "error",
            confirmButtonText: "Okay",
        });

        return;
    }

    if (new_password !== confirm_password) {
        Swal.fire({
            title: "Error!",
            text: "Passwords do not match.",
            icon: "error",
            confirmButtonText: "Okay",
        });
        return;
    }

    $.ajax({
        url: "/api/user/reset-password/" + user_id,
        type: "PUT",
        dataType: "json",
        data: {
            password: confirm_password,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: (response) => {
            if (response.success) {
                $("#resetPasswordModal").modal("hide");
                Swal.fire({
                    title: "Success!",
                    text: "Password has been reset successfully.",
                    icon: "success",
                    confirmButtonText: "Okay",
                });
                loadUsersData();
                $('#new_password').val('');
                $('#confirm_password').val('');
            } else {
                console.log(response.message);
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: "Error!",
                text: "Failed to reset password.",
                icon: "error",
                confirmButtonText: "Okay",
            });
            console.error("AJAX Error: " + status + error);
        },
    });
});


    // ketika tombol hapus diklik
    $(document).on("click", ".btn-delete", function () {
        let user_id = $(this).data("user_id");
        console.log(user_id);
        $("#deleteModal").modal("show");
        $("#delete_user_id").val(user_id);
    });

    // ketika tombol konfirmasi hapus diklik
    $(document).on("click", ".confirmed-delete", function () {
        let user_id = $("#delete_user_id").val();

        $.ajax({
            url: "/api/user/delete-user/" + user_id,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    // console.log(response.message);
                    $("#deleteModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been deleted successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadUsersData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Failed to delete data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
