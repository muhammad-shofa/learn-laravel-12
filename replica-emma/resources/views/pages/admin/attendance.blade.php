@extends('layouts.app')

@section('title', 'Attendance')
@vite(['resources/js/attendance.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Attendance</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Attendace Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table" id="attendanceTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Status</th>
                                <th style="width: 150px">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center border">
                                    <p class="fw-bold no-data-yet">No attendance data yet</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

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

        </div>
    </div>
</main>
@endsection