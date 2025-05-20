import Swal from "sweetalert2";

$(document).ready(function () {
    // function to get all employees data
    // v1
    // function loadEmployeesData() {
    //     $.ajax({
    //         url: "/api/employee/get-employees",
    //         type: "GET",
    //         dataType: "json",
    //         success: (response) => {
    //             if (response.success) {
    //                 let employeesTable = $("#employeeTableData tbody");
    //                 let no = 0;
    //                 employeesTable.empty();
    //                 $.each(response.data, (index, employee) => {
    //                     no++;
    //                     employeesTable.append(`
    //                         <tr>
    //                             <td>${no}</td>
    //                             <td>${employee.employee_code}</td>
    //                             <td>${employee.full_name}</td>
    //                             <td>${employee.email}</td>
    //                             <td>${employee.phone}</td>
    //                             <td>${employee.position}</td>
    //                             <td>${employee.gender}</td>
    //                             <td>${employee.join_date}</td>
    //                             <td>${employee.status}</td>
    //                             <td>
    //                                 <button class="btn-edit btn btn-primary" data-employee_id="${employee.id}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen"></i></button>
    //                                 <button class="btn-delete btn btn-danger" data-employee_id="${employee.id}"><i class="fa-solid fa-trash"></i></button>
    //                             </td>
    //                         <tr>
    //                         `);
    //                 });
    //             } else {
    //                 console.log(response.error);
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("AJAX Error: " + status + error);
    //         },
    //     });
    // }

    function loadEmployeesData() {
        $("#employeeTableData").DataTable({
            destroy: true, // agar bisa reload ulang
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/employee/get-employees",
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
                { data: "employee_code" },
                { data: "full_name" },
                { data: "email" },
                { data: "phone" },
                { data: "position" },
                { data: "gender" },
                { data: "join_date" },
                { data: "status" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-employee_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-delete btn btn-danger" data-employee_id="${row.id}">
                                <i class="fa-solid fa-trash"></i>
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
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been added successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                } else {
                    console.log(response.error);
                    $("#addEmployeeForm")[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Failed!",
                    text: "Failed to add new data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });

    // ketika tombol edit diklik
    $(document).on("click", ".btn-edit", function () {
        let employee_id = $(this).data("employee_id");
        $.ajax({
            url: "/api/employee/get-employee/" + employee_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#edit_employee_id").val(response.data.id);
                    $("#edit_full_name").val(response.data.full_name);
                    $("#edit_email").val(response.data.email);
                    $("#edit_phone").val(response.data.phone);
                    $("#edit_position").val(response.data.position);
                    $("#edit_gender").val(response.data.gender);
                    $("#edit_join_date").val(response.data.join_date);
                    $("#edit_status").val(response.data.status);
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
        let employee_id = $("#edit_employee_id").val();
        let full_name = $("#edit_full_name").val();
        let email = $("#edit_email").val();
        let phone = $("#edit_phone").val();
        let position = $("#edit_position").val();
        let gender = $("#edit_gender").val();
        let join_date = $("#edit_join_date").val();
        let status = $("#status").val();

        $.ajax({
            url: "/api/employee/update-employee/" + employee_id,
            type: "PUT",
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
                    $("#editModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been updated successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadEmployeesData();
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
        let employee_id = $(this).data("employee_id");
        $("#deleteModal").modal("show");
        $("#delete_employee_id").val(employee_id);
    });

    // ketika tombol konfirmasi hapus diklik
    $(document).on("click", ".confirmed-delete", function () {
        let employee_id = $("#delete_employee_id").val();

        $.ajax({
            url: "/api/employee/delete-employee/" + employee_id,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                if (response.success) {
                    console.log(response.message);
                    $("#deleteModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Data has been deleted successfully.",
                        icon: "success",
                        confirmButtonText: "Oke",
                    });
                    loadEmployeesData();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                Swal.fire({
                    title: "Error!",
                    text: "Failed to delete data.",
                    icon: "error",
                    confirmButtonText: "Oke",
                });
            },
        });
    });
});
