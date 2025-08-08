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
            <!-- <button type="button" class="btn-add btn btn-success my-3">Add User</button> -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title">User Table</h3>
                        <button type="button" class="btn-add btn btn-success mx-3">Add User</button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <div class="table-responsive">
                        <table class="display text-start" id="userTableData">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Employee Code</th>
                                    <th>Username</th>
                                    <th>Try Login</th>
                                    <th>Status Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
                    <input type="hidden" name="user_id" id="edit_user_id">
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
                        <select class="form-select" id="edit_status_login" require>
                            <option selected value="active">Active</option>
                            <option value="nonactive">Nonactive</option>
                        </select>
                    </div>
                </form>
            </x-modal>

            <!-- reset password modal start -->
            @php
            $modalFooter = '<button type="button" class="save-new-password btn btn-success">Save</button>';
            @endphp
            <x-modal id="resetPasswordModal" title="Reset User Password" :footer="$modalFooter">
                <form id="resetPasswordForm">
                    <input type="hidden" name="user_id" id="reset_user_id">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" require />
                            <span class="input-group-text" id="show-new-password"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" require />
                            <span class="input-group-text" id="show-confirm-password"><i class="fa-solid fa-eye"></i></span>
                        </div>
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