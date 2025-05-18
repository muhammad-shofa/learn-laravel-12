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
                <!-- <div class="col-md-6"> -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2>Welcome <b class="bg-warning px-2 text-white">{{ $user->username }}</b>, let's work with your team!</h2>
                    </div>
                </div>
                <!-- </div> -->
            </div>
            <!-- /.row -->

            <!-- card employee start -->
            <div class="row mt-5">
                <!-- Start col -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Employee Profile</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <tr>
                                            <td><strong>Employee Code</strong></td>
                                            <td>{{ $employee->employee_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Full Name</strong></td>
                                            <td>{{ $employee->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>{{ $employee->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. HP</strong></td>
                                            <td>{{ $employee->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position</strong></td>
                                            <td>{{ $employee->position }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender</strong></td>
                                            <td>{{ ucfirst($employee->gender) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Join Date</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($employee->join_date)->translatedFormat('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>
                                                @if($employee->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                                @else
                                                <span class="badge bg-danger">Nonactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Container-->
    </div>
</main>
@endsection