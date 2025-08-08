@extends('layouts.app') @section('title', 'Attendance')
@vite(['resources/js/attendance.js']) @section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Attendance</h3>
                    <!-- Tombol -->
                    <button
                        class="btn btn-secondary mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalHolidaySetting"
                    >
                        <i class="fas fa-cog"></i> Weekly Holiday Setting
                    </button>
                    <div class="my-2">
                        <h5 id="content-max-holidays"></h5>
                        <h6 id="content-days"></h6>
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
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <h3 class="card-title">Attendance Table</h3>
                        <button class="btn btn-danger" id="btnExportAttendance">Export <i class="fa-solid fa-file-lines"></i></button>
                    </div>
                </div>

                <!-- Modal -->
                <!-- <div
                    class="modal fade"
                    id="modalHolidaySetting"
                    tabindex="-1"
                    aria-labelledby="modalLabel"
                    aria-hidden="true"
                >
                    <div class="modal-dialog">

                     -->
                <!-- weekly holiday setting modal -->
                @php $modalFooter = '
                <button
                    type="button"
                    class="save-weekly-setting btn btn-primary"
                >
                    Save
                </button>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>
                '; @endphp
                <x-modal
                    id="modalHolidaySetting"
                    title="Weekly Holiday Setting"
                    :footer="$modalFooter"
                >
                    <form id="formHolidaySetting">
                        <!-- max holiday -->
                        <div class="mb-3">
                            <label class="form-label">Max Holiday</label>
                            <div class="d-flex flex-wrap gap-2">
                                @for ($i = 1; $i <= 7; $i++)
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="max_holidays_per_week"
                                        id="dayLimit{{ $i }}"
                                        value="{{ $i }}"
                                        required
                                    />
                                    <label
                                        class="form-check-label"
                                        for="dayLimit{{ $i }}"
                                        >{{ $i }} D</label
                                    >
                                </div>
                                @endfor
                            </div>
                        </div>

                        <!-- select day -->
                        <div class="mb-3">
                            <label class="form-label">Select Day</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']
                                as $day)
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input day-checkbox"
                                        type="checkbox"
                                        name="days[]"
                                        value="{{ $day }}"
                                        id="day_{{ $day }}"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="day_{{ $day }}"
                                        >{{ $day }}</label
                                    >
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </x-modal>

                <!-- </div> -->
                <!-- </div> -->

                {{--  --}}
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <div class="table-responsive">
                        <table class="display nowrap" id="attendanceTableData">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Employee Code</th>
                                    <th>Full Name</th>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Clock In Status</th>
                                    <th>Clock Out Status</th>
                                    <th>Work Duration (Min)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- edit modal -->
            @php $modalFooter = '<button
                type="button"
                class="save-edit btn btn-success"
            >
                Save Edit</button
            >'; @endphp
            <x-modal
                id="editModal"
                title="Edit Attendance"
                :footer="$modalFooter"
            >
                <form id="editAttendanceForm">
                    <input type="hidden" id="attendance_id" />
                    <div class="mb-3">
                        <label for="edit_clock_in" class="form-label"
                            >Clock In</label
                        >
                        <input
                            type="time"
                            step="1"
                            class="form-control"
                            id="edit_clock_in"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="edit_clock_out" class="form-label"
                            >Clock Out</label
                        >
                        <input
                            type="time"
                            step="1"
                            class="form-control"
                            id="edit_clock_out"
                        />
                    </div>
                    <div class="md-3">
                        <label for="edit_clock_in_status" class="form-label"
                            >Clock In Status</label
                        >
                        <select
                            class="form-select"
                            id="edit_clock_in_status"
                            require
                        >
                            <option value="ontime">Ontime</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>
                    <div class="md-3">
                        <label for="edit_clock_out_status" class="form-label"
                            >Clock Out Status</label
                        >
                        <select
                            class="form-select"
                            id="edit_clock_out_status"
                            require
                        >
                            <option value="ontime">Ontime</option>
                            <option value="early">Early</option>
                            <option value="late">Late</option>
                            <option value="no_clock_out">No Clock Out</option>
                        </select>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</main>
@endsection
