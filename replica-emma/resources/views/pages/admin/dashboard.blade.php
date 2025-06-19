@extends('layouts.app')

@section('title', 'Dashboard')
@vite(['resources/js/dashboard.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                    <div class="d-flex gap-3 mt-3">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <button class="btn-reset-filter btn btn-success">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
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
                    <div class="info-box">
                        <span class="info-box-icon text-bg-primary shadow-sm">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Employees</span>
                            <span class="info-box-number" id="employee_counts">

                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-danger shadow-sm">
                            <i class="fa-solid fa-user-clock" style="color: #ffffff;"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Late</span>
                            <span class="info-box-number" id="late_counts"></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-warning shadow-sm">
                            <i class="fa-solid fa-calendar" style="color: #ffffff;"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Time Off Request</span>
                            <span class="info-box-number" id="time_off_counts"></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!--begin::Row-->
            <div class="row">
                <!-- Start col -->
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Attendance</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0" id="attendanceDashboardTableData">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Clock in</th>
                                            <th>Clock In Status</th>
                                            <th>Clock Out Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="/attendance" class="btn btn-sm btn-primary float-start">
                                View All Attendances
                            </a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mt-4">
                <!-- Start col -->
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div id="monthlyAttendanceChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->

            <!-- filter modal start -->
            @php
            $modalFooter = '<button type="button" class="btn-apply-filter btn btn-primary">Apply Filter</button>';
            @endphp
            <x-modal id="filterModal" title="Filter" :footer="$modalFooter">
                <form id="filterForm">
                    <div class="mb-3">
                        <label for="filter_month" class="form-label">Month</label>
                        <select class="form-select" id="filter_month" name="month" required>
                            <option value="">-- Select Month --</option>
                            <!-- Buat dari 1–12 -->
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter_year" class="form-label">Year</label>
                        <select class="form-select" id="filter_year" name="year" required>
                            <option value="">-- Select Year --</option>
                            @for ($year = 2020; $year <= now()->year; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                    </div>
                </form>
            </x-modal>
        </div>
        <!--end::Container-->
    </div>
</main>
@endsection