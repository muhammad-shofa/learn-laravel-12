@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
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
                            <span class="info-box-number">
                                14
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
                            <span class="info-box-text">Late Today</span>
                            <span class="info-box-number">2</span>
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
                            <span class="info-box-number">5</span>
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
                    <!--begin::Latest Order Widget-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Today's Attendance</h3>
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
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Clock-in</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p>Madam</p>
                                            </td>
                                            <td>09:17</td>
                                            <td><span class="badge text-bg-danger">late</span></td>
                                            <td>
                                                <div id="table-sparkline-7"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Bagas</p>
                                            </td>
                                            <td>08:42</td>
                                            <td><span class="badge text-bg-danger">late</span></td>
                                            <td>
                                                <div id="table-sparkline-7"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Pororo</p>
                                            </td>
                                            <td>08:07</td>
                                            <td><span class="badge text-bg-success">ontime</span></td>
                                            <td>
                                                <div id="table-sparkline-1"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Andreas</p>
                                            </td>
                                            <td>08:05</td>
                                            <td><span class="badge text-bg-success">ontime</span></td>
                                            <td>
                                                <div id="table-sparkline-1"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Kasi</p>
                                            </td>
                                            <td>07:57</td>
                                            <td><span class="badge text-bg-success">ontime</span></td>
                                            <td>
                                                <div id="table-sparkline-1"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="#0" class="btn btn-sm btn-primary float-start">
                                View All Attendances
                            </a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
</main>
@endsection