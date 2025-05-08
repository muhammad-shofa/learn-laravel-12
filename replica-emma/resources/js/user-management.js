import Swal from "sweetalert2";

$(document).ready(function () {
    // function to get all users data
    function loadUsersData() {
        $.ajax({
            url: "/api/user/get-users",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let usersTable = $("#userTableData tbody");
                    let no = 0;
                    usersTable.empty();
                    $.each(response.data, (index, user) => {
                        no++;
                        usersTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${
                                    user.employee
                                        ? user.employee.employee_code
                                        : "EMP Not Found"
                                }</td>
                                <td>${user.username}</td>
                                <td>${user.role}</td>
                                <td>${user.try_login}</td>
                                <td>${user.status_login}</td>
                                <td>
                                    <button class="btn-edit btn btn-primary" data-user_id="${
                                        user.id
                                    }" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-delete btn btn-danger" data-user_id="${
                                        user.id
                                    }" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
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

    // load users data
    loadUsersData();

    // ambil data employee code untuk ditampilkan pada select
    function selectEmployeeCode(select_id) {
        $.ajax({
            url: "/api/employee/get-employees",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let employeeCodeSelect = $(select_id);
                    employeeCodeSelect.empty();
                    employeeCodeSelect.append(`
                    <option selected value="">-- Select Employee --</option>
                `);
                    $.each(response.data, (index, employee) => {
                        employeeCodeSelect.append(`
                        <option value="${employee.id}">${employee.employee_code} - ${employee.full_name}</option>
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
                Swal.fire({
                    title: "Failed!",
                    text: "Failed to add new data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
                console.error("AJAX Error: " + status + error);
            },
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
                        response.data.employee
                            ? response.data.employee.id
                            : ""
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
