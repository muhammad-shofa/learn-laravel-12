@extends('layouts.app') @section('title', 'Dashboard')
@vite(['resources/js/dashboard.js']) @section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="/attendance" class="info-box text-decoration-none">
                        <span class="info-box-icon bg-success shadow-lg">
                            <i
                                class="fa-solid fa-users-viewfinder"
                                style="color: #ffffff"
                            ></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Attendance</span>
                        </div>
                        <!-- /.info-box-content -->
                    </a>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="/time-off" class="info-box text-decoration-none">
                        <span class="info-box-icon bg-warning shadow-lg">
                            <i
                                class="fa-solid fa-calendar"
                                style="color: #ffffff"
                            ></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Time Off</span>
                        </div>
                        <!-- /.info-box-content -->
                    </a>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="/salary" class="info-box text-decoration-none">
                        <span class="info-box-icon bg-primary shadow-lg">
                            <i
                                class="fa-solid fa-credit-card"
                                style="color: #ffffff"
                            ></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Salary</span>
                        </div>
                        <!-- /.info-box-content -->
                    </a>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Start cards row -->
            <div class="row mt-5">
                <!-- Employee Profile Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <h5 class="mb-0">Employee Profile</h5>
                                <button
                                    class="btn-show-edit-employee-modal btn btn-secondary"
                                    data-employee_id="{{ $employee->id }}"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-borderless align-middle"
                                >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Employee Code</strong>
                                            </td>
                                            <td>
                                                {{ $employee->employee_code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Full Name</strong></td>
                                            <td>{{ $employee->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td id="employee_email_data">
                                                {{ $employee->email }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. HP</strong></td>
                                            <td id="employee_phone_data">
                                                {{ $employee->phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position</strong></td>
                                            <td>{{ $employee->position }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender</strong></td>
                                            <td>
                                                {{ ucfirst($employee->gender) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Join Date</strong></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($employee->join_date)->translatedFormat('d F Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>
                                                @if($employee->status ===
                                                'active')
                                                <span class="badge bg-success"
                                                    >Active</span
                                                >
                                                @else
                                                <span class="badge bg-danger"
                                                    >Nonactive</span
                                                >
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Your Attendance</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="display text-start"
                                    id="dashboardAttendanceTableData"
                                >
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">No</th>
                                            <th>Date</th>
                                            <th>Clock In</th>
                                            <th>Clock Out</th>
                                            <th>Clock In Status</th>
                                            <th>Clock Out Status</th>
                                            <th>Work Duration (Min)</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Off Summary Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Time Off Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-borderless align-middle"
                                >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Total Quota</strong>
                                            </td>
                                            <td id="employee_total_quota"></td>
                                    
                                        </tr>
                                        <tr>
                                            <td><strong>Used</strong></td>
                                            <td id="employee_used_quota"></td>
                                            <!-- dummy -->
                                        </tr>
                                        <tr>
                                            <td><strong>Remaining</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-info text-dark" id="employee_remaining_quota"
                                                >
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Last Time Off</strong>
                                            </td>
                                            <td id="employee_last_time_off"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End cards row -->

            <!-- Start Edit Modal -->
            @php $modalFooter = '<button
                type="button"
                class="btn-edit-employee-data btn btn-primary"
            >
                Save</button
            >'; @endphp
            <x-modal
                id="editModal"
                title="Edit your data"
                :footer="$modalFooter"
            >
                <form id="editEmployeeForm">
                    <input
                        type="hidden"
                        id="edit_employee_id"
                        value="{{ $employee->id }}"
                    />
                    <div class="mb-3">
                        <label for="edit_employee_email" class="form-label"
                            >Email</label
                        >
                        <input
                            type="email"
                            class="form-control"
                            id="edit_employee_email"
                            name="edit_employee_email"
                            value="{{ $employee->email }}"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="edit_employee_phone" class="form-label"
                            >Phone</label
                        >
                        <input
                            type="number"
                            class="form-control"
                            id="edit_employee_phone"
                            name="edit_employee_phone"
                            value="{{ $employee->phone }}"
                            required
                        />
                    </div>
                </form>
            </x-modal>
        </div>
        <!--end::Container-->
    </div>
</main>
@endsection
