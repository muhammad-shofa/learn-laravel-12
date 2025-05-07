@extends('layouts.app')

@section('title', 'User Management')
@vite(['resources/js/employee-management.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Employee Management</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <button type="button" class="btn-add btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button>
            <!-- <button type="button" class="btn-add btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button> -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Employee Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table" id="employeeTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Gender</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th style="width: 150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center border">
                                    <p class="fw-bold no-data-yet">No employee data yet</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- add employee -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Add User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addEmployeeForm">
                                <!-- Employee code digenerate otomatis dari backend -->
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full name</label>
                                    <input type="text" class="form-control" id="full_name" />
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" />
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="number" class="form-control" id="phone" />
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <select class="form-select" id="position">
                                        <!--
                                    Pengembangan : tambahkan table position di database untuk menampung data position yang
                                    nantinya akan ditampilkan di dropdown ini     
                                        -->
                                        <option value="hr">HR</option>
                                        <option value="senior_programmer">Senior Programmer</option>
                                        <option value="junior_programmer">Junior Programmer</option>
                                        <option value="office_boy">Office Boy</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">gender</label>
                                    <select class="form-select" id="gender">
                                        <option selected value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="join_date" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="join_date" />
                                </div>
                                <div class="md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status">
                                        <option selected value="active">Active</option>
                                        <option value="inactive">inactive</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="save-add btn btn-success">Add New Employee</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- delete modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Delete Employee</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="delete_employee_id">
                            <p>Are you sure you want to remove this data?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="button" class="confirmed-delete btn btn-danger">Yes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection