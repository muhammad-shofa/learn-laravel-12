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
                                <td>${user.employee_id}</td>
                                <td>${user.username}</td>
                                <td>${user.role}</td>
                                <td>${user.try_login}</td>
                                <td>${user.status_login}</td>
                                <td>
                                    <button class="btn btn-primary" data-user_id="${user.id}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-danger" data-user_id="${user.id}" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
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

    // ketika tombol btn-add diklik maka
    // ambil data employee code untuk ditampilkan pada select
    $(document).on("click", ".btn-add", function () {
        $("#addModal").modal("show");

        $.ajax({
            url: "/api/employee/get-employees",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let employeeCodeSelect = $("#employee_code");
                    employeeCodeSelect.empty();
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
                    $("#addUserForm")[0].reset();
                    loadUsersData();
                } else {
                    $("#addUserForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
