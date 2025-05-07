$(document).ready(function () {
    // function to get all employees data
    function loadEmployeesData() {
        $.ajax({
            url: "/api/employee/get-employees",
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    let employeesTable = $("#employeeTableData tbody");
                    let no = 0;
                    employeesTable.empty();
                    $.each(response.data, (index, employee) => {
                        no++;
                        employeesTable.append(`
                            <tr>
                                <td>${no}</td>
                                <td>${employee.employee_code}</td>
                                <td>${employee.full_name}</td>
                                <td>${employee.email}</td>
                                <td>${employee.phone}</td>
                                <td>${employee.position}</td>
                                <td>${employee.gender}</td>
                                <td>${employee.join_date}</td>
                                <td>${employee.status}</td>
                                <td>
                                    <button class="btn btn-primary" data-employee_id="${employee.id}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-delete btn btn-danger" data-employee_id="${employee.id}"><i class="fa-solid fa-trash"></i></button>
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

    // load employees data
    loadEmployeesData();


    $(document).on("click", ".save-add", function () {
        let full_name = $("#full_name").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let position = $("#position").val();
        let gender = $("#gender").val();
        let join_date = $("#join_date").val();
        let status = $("#status").val();
        $.ajax({
            url: "/api/employee/add-employee",
            type: "POST",
            dataType: "json",
            data: {
                full_name: full_name,
                email: email,
                phone: phone,
                position: position,
                gender: gender,
                join_date: join_date,
                status: status,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    $("#addModal").modal("hide");
                    $("#addEmployeeForm")[0].reset();
                    loadEmployeesData();
                } else {
                    console.log(response.error);
                    $("#addEmployeeForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });

    $(document).on("click", ".btn-delete", function () {
        let employee_id = $(this).data("employee_id");
        $("#deleteModal").modal("show");
        $("#delete_employee_id").val(employee_id);
    });

    $(document).on("click", ".confirmed-delete", function () {
        let employee_id = $("#delete_employee_id").val();

        $.ajax({
            url: "/api/employee/delete-employee/" + employee_id,
            type: "delete",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#deleteModal").modal("hide");
                    loadEmployeesData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
            },
        });
    });
});
