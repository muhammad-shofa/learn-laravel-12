@extends('layouts.app-employee')


@section('title', 'Time Off')
@vite(['resources/js/time-off.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm">
                    <h3 class="mb-0">Time Off</h3>
                </div>
                <div class="col-sm text-end">
                    <a href="/api/auth/logout" class="btn btn-danger">
                        <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            {{-- navigation menu --}}
            <x-menu/>
            <div class="d-flex gap-5 justify-content-evenly flex-wrap mt-5">
                <div class="row">
                    <!-- Start col -->
                    <div class="col">
                        <div class="card mb-4 card-time-off">
                            <div class="card-header bg-warning">
                                <h3 class="card-title">New Time Off Request</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-3">
                                <form id="addTimeOffForm">
                                    <input type="hidden" id="time_off_employee_id_hidden" value="{{ $employee->id }}">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" require />
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="end_date" require />
                                    </div>
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason</label>
                                        <textarea name="reason" class="form-control reason-textarea" id="reason"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="submit-time-off-request btn btn-success">Submit Request</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Start col -->
                    <div class="col">
                        <div class="card mb-4 card-time-off">
                            <div class="card-header bg-warning">
                                <h3 class="card-title">History Time Off Request</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table id="historyTimeOffRequestData" class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Request Date</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection