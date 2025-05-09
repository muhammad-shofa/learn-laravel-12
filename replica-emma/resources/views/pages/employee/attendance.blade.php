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
            <div class="d-flex gap-5 justify-content-evenly">
                <div class="card mb-4 w-50" style="min-height: 400px;">
                    <div class="card-header">
                        <h3 class="card-title">Clock In</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3 text-center mt-5">
                        <!-- <h2>Clock In</h2> -->
                        <input type="hidden" id="attendance_employee_id" value="{{ $user->employee_id }}">
                        <h3 class="realtime-date" style="font-size: 20px; font-weight: bold;"></h3>
                        <h1 class="realtime-clock" style="font-weight: bold;" class="my-3"></h1>
                        <button class="btn-clock-in btn btn-success">Clock In</button>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card mb-4 w-50">
                    <div class="card-header">
                        <h3 class="card-title">Clock Out</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-3 text-center mt-5">
                        <h3 class="realtime-date" style="font-size: 20px; font-weight: bold;"></h3>
                        <h2>16:00</h2>
                        <button class="btn-clock-out btn btn-success">Clock Out</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

        </div>
    </div>
</main>
@endsection