@extends('layouts.app')

@section('title', 'Time Off')
@vite(['resources/js/time-off.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Time Off</h3>
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
                    <h3 class="card-title">Time Off Requests Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <table class="display nowrap" id="timeOffTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Request Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- edit modal -->
            {{-- @php
            $modalFooter = '<button type="button" class="save-edit btn btn-success">Save Edit</button>';
            @endphp
            <x-modal id="editModal" title="Edit Attendance" :footer="$modalFooter">
                <form id="editAttendanceForm">
                    <input type="hidden" id="attendance_id">
                    <div class="mb-3">
                        <label for="edit_clock_in" class="form-label">Clock In</label>
                        <input type="time" step="1" class="form-control" id="edit_clock_in" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_clock_out" class="form-label">Clock Out</label>
                        <input type="time" step="1" class="form-control" id="edit_clock_out" />
                    </div>
                    <div class="md-3">
                        <label for="edit_clock_in_status" class="form-label">Clock In Status</label>
                        <select class="form-select" id="edit_clock_in_status" require>
                            <option value="ontime">Ontime</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>
                    <div class="md-3">
                        <label for="edit_clock_out_status" class="form-label">Clock Out Status</label>
                        <select class="form-select" id="edit_clock_out_status" require>
                            <option value="ontime">Ontime</option>
                            <option value="early">Early</option>
                            <option value="late">Late</option>
                            <option value="no_clock_out">No Clock Out</option>
                        </select>
                    </div>
                </form>
            </x-modal> --}}

        </div>
    </div>
</main>
@endsection