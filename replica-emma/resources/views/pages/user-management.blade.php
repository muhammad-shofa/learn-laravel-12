@extends('layouts.app')

@section('title', 'User Management')
@vite(['resources/js/user-management.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">User Management</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <button type="button" class="btn-add btn btn-success my-3">Add User</button>
            <!-- <button type="button" class="btn-add btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button> -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">User Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table" id="userTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Try Login</th>
                                <th>Status</th>
                                <th style="width: 150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center border">
                                    <p class="fw-bold no-data-yet">No users yet</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- add modal start -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Add User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm">
                                <div class="mb-3">
                                    <label for="employee_code" class="form-label">Employee Code</label>
                                    <select class="form-select" id="employee_code">
                                        <option selected value="#">- None -</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" />
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" />
                                </div>
                                <div class="md-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role">
                                        <option selected value="admin">Admin</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="save-add btn btn-primary">Save User</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</main>
@endsection