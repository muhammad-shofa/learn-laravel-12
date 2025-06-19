@extends('layouts.app-employee')

@section('title', 'Attendance')
@vite(['resources/js/attendance.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm">
                        <h3 class="mb-0">Attendance</h3>
                    </div>
                    <div class="col-sm text-end">
                        <a href="/api/auth/logout" class="btn btn-danger">
                            <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                        </a>
                    </div>
                </div>
                <!--end::Row-->
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
            <div id="alert-dayoff"></div>
            <div class="d-flex gap-5 justify-content-evenly flex-wrap mt-5">
                <div class="row border border-5 border-dark rounded p-0">
                    <div class="p-3 col">
                        <input type="hidden" id="attendance_employee_id" value="{{ $user->employee_id }}">
                        <p class="pt-3">Clock In</p>
                        <h3 class="realtime-date" style="font-size: 20px; font-weight: bold;" class="my-5"></h3>
                        <h1 class="realtime-clock" style="font-weight: bold;"></h1>
                        <button id="btn-clock-in" class="mb-5">Clock In</button>
                        <div class="rounded">
                            <p>Clock in status</p>
                            <h5 id="text-clock-in-status-attendance" class="p-3 bg-secondary text-white rounded"></h5>
                        </div>
                    </div>
                    <div class="col p-0 m-0">
                        <img src="/img/clock-3d.jpeg" alt="clock" class="rounded p-0 m-0" width="400px" height="400px">
                    </div>
                </div>
                <div class="row border border-5 border-dark rounded p-0">
                    <div class="p-3 col">
                        <input type="hidden" id="attendance_employee_id" value="{{ $user->employee_id }}">
                        <p class="pt-3">Clock Out</p>
                        <h3 class="realtime-date" style="font-size: 20px; font-weight: bold;" class="my-5"></h3>
                        <h1 class="" style="font-weight: bold;">16:00:00</h1>
                        <button id="btn-clock-out" class="mb-5">Clock Out</button>
                        <div class="rounded">
                            <p>Clock in status</p>
                            <h5 id="text-clock-out-status-attendance" class="p-3 bg-secondary text-white rounded"></h5>
                        </div>
                    </div>
                    <div class="col p-0 m-0">
                        <img src="/img/clock-out-1.jpg" alt="clock" class="rounded p-0 m-0" width="400px" height="400px">
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection