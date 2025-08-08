@extends('layouts.app')

@section('title', 'Employee Management')
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
            <!-- <button type="button" class="btn-add btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button> -->
            <!-- <button type="button" class="btn-add btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button> -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <!-- <div class="d-flex align-items-center"> -->
                            <h3 class="card-title">Employee Table</h3>
                        <!-- </div> -->
                         <div>
                             <button type="button" class="btn-add btn btn-success mx-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button>
                             <button class="btn btn-danger" id="btnExportEmployee">Export <i class="fa-solid fa-file-lines"></i></button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <div class="table-responsive">
                        <table class="display" id="employeeTableData">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- add employee -->
            @php
            $modalFooter = '<button type="button" class="save-add btn btn-success">Save Employee</button>';
            @endphp
            <x-modal id="addModal" title="Add Employee" :footer="$modalFooter">
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
            </x-modal>

            <!-- edit modal -->
            @php
            $modalFooter = '<button type="button" class="save-edit btn btn-success">Save Edit</button>';
            @endphp
            <x-modal id="editModal" title="Edit Employee" :footer="$modalFooter">
                <form id="addEmployeeForm">
                    <!-- Employee code digenerate otomatis dari backend -->
                     <input type="hidden" name="employee_id" id="edit_employee_id">
                    <div class="mb-3">
                        <label for="edit_full_name" class="form-label">Full name</label>
                        <input type="text" class="form-control" id="edit_full_name" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="number" class="form-control" id="edit_phone" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label">Position</label>
                        <select class="form-select" id="edit_position">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label">gender</label>
                        <select class="form-select" id="edit_gender">
                            <option selected value="M">M</option>
                            <option value="F">F</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_join_date" class="form-label">Join Date</label>
                        <input type="date" class="form-control" id="edit_join_date" />
                    </div>
                    <div class="md-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status">
                            <option selected value="active">Active</option>
                            <option value="inactive">inactive</option>
                        </select>
                    </div>
                </form>
            </x-modal>

            <!-- delete modal -->
            @php
            $modalFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <button type="button" class="confirmed-delete btn btn-danger">Yes</button>';
            @endphp
            <x-modal id="deleteModal" title="Delete Employee" :footer="$modalFooter">
                <input type="hidden" id="delete_employee_id">
                <p>Are you sure you want to remove this data?</p>
            </x-modal>

        </div>
    </div>
</main>
@endsection