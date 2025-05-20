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
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">User Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <table class="display text-start" id="userTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Try Login</th>
                                <th>Status Login</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <tr>
                                <td colspan="6" class="text-center border">
                                    <p class="fw-bold no-data-yet">No users yet</p>
                                </td>
                            </tr>
                        </tbody> -->
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- add modal start -->
            @php
            $modalFooter = '<button type="button" class="save-add btn btn-success">Save User</button>';
            @endphp
            <x-modal id="addModal" title="Add User" :footer="$modalFooter">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code</label>
                        <select id="employee_code">
                            <!-- <option selected value="#">- None -</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" require />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" require />
                    </div>
                    <div class="md-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" require>
                            <option selected value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                </form>
            </x-modal>

            <!-- edit modal start -->
            @php
            $modalFooter = '<button type="button" class="save-edit btn btn-success">Save Edit</button>';
            @endphp
            <x-modal id="editModal" title="Edit User" :footer="$modalFooter">
                <form id="addUserForm">
                    <!-- Employee code digenerate otomatis dari backend -->
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label for="edit_employee_code" class="form-label">Employee Code</label>
                        <select class="form-select" id="edit_employee_code">
                            <option selected value="#">- None -</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" require />
                    </div>
                    <div class="md-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" require>
                            <option selected value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_try_login" class="form-label">Try Login</label>
                        <input type="text" class="form-control" id="edit_try_login" require />
                    </div>
                    <div class="mb-3">
                        <label for="edit_status_login" class="form-label">Status Login</label>
                        <input type="text" class="form-control" id="edit_status_login" require />
                    </div>

                </form>
            </x-modal>

            <!-- delete modal start -->
            @php
            $modalFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <button type="button" class="confirmed-delete btn btn-danger">Yes</button>';
            @endphp
            <x-modal id="deleteModal" title="Delete User" :footer="$modalFooter">
                <input type="hidden" id="delete_user_id">
                <p>Are you sure you want to remove this data?</p>
            </x-modal>

        </div>
    </div>
</main>
@endsection