@extends('layouts.app') @section('title', 'Report')
@vite(['resources/js/report.js']) @section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Report</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link active"
                        id="attendance-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#attendance"
                        type="button"
                        role="tab"
                    >
                        Attendances
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="timeoff-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#timeoff"
                        type="button"
                        role="tab"
                    >
                        Time Off
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="salary-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#salary"
                        type="button"
                        role="tab"
                    >
                        Salary
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabsContent">
                <!-- Attendance Report -->
                <div
                    class="tab-pane fade show active"
                    id="attendance"
                    role="tabpanel"
                >
                    <div class="text-end">
                        <button
                            class="btn btn-danger my-3"
                            id="btnDownloadAttendanceReport"
                        >
                            Download PDF
                        </button>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div id="attendanceCalendar"></div>
                        </div>
                    </div>
                </div>

                <!-- Time Off Report -->
                <div class="tab-pane fade" id="timeoff" role="tabpanel">
                    <div class="d-flex gap-3 mt-3">
                        <button
                            class="btn-show-filter btn btn-warning"
                            data-target="timeoff"
                        >
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <button class="btn-reset-filter btn btn-success">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <h3><span id="currentMonthTimeOff"></span> <span id="currentYearTimeOff"></span></h3>
                        <button
                            class="btn btn-danger my-3"
                            id="btnDownloadTimeOffReport"
                        >
                            Download PDF
                        </button>
                    </div>
                    {{-- <div class="text-end">
                        <button class="btn btn-danger my-3">
                            Download PDF
                        </button>
                    </div> --}}
                    <div id="timeOffChart" class="mb-4"></div>
                </div>

                <!-- Salary Report -->
                <div class="tab-pane fade" id="salary" role="tabpanel">
                    <div class="d-flex gap-3 mt-3">
                        <button
                            class="btn-show-filter btn btn-warning"
                            data-target="salary"
                        >
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <button class="btn-reset-filter btn btn-success">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <h3><span id="currentMonth"></span> <span id="currentYear"></span></h3>
                        <button
                            class="btn btn-danger my-3"
                            id="btnDownloadSalaryReport"
                        >
                            Download PDF
                        </button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card text-white border border-success">
                                <div class="card-body text-success">
                                    <h5 class="card-title">
                                        Total salary paid this month
                                    </h5>
                                    <br />
                                    <p
                                        class="card-text fs-4"
                                        id="salary-paid-content"
                                    ></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white border border-danger">
                                <div class="card-body text-danger">
                                    <h5 class="card-title">

                                        Total salary deductions this month
                                    </h5>
                                    <br />
                                    <p
                                        class="card-text fs-4"
                                        id="salary-deduction-content"
                                    ></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white border border-primary">
                                <div class="card-body text-primary">
                                    <h5 class="card-title">
                                        Total salary bonus this month
                                    </h5>
                                    <br />
                                    <p
                                        class="card-text fs-4"
                                        id="salary-bonus-content"
                                    ></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="salaryChart" class="mb-4"></div>
                </div>
            </div>

            <!-- Tombol trigger offcanvas, hidden -->
            <button
                type="button"
                class="d-none"
                id="triggerOffcanvasAttendance"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasAttendance"
                aria-controls="offcanvasAttendance"
            ></button>

            <!-- Offcanvas Attendance Detail -->
            <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="offcanvasAttendance"
                aria-labelledby="offcanvasAttendanceLabel"
            >
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasAttendanceLabel">
                        Attendances Detail
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body" id="attendanceDetailContent">
                    <p>Loading attendances data...</p>
                </div>
            </div>

            <!-- filter modal start -->
            @php $modalFooter = '<button
                type="button"
                class="btn-apply-filter btn btn-primary"
            >
                Apply Filter</button
            >'; @endphp
            <x-modal id="filterModal" title="Filter" :footer="$modalFooter">
                <form id="filterForm">
                    <div class="mb-3">
                        <label for="filter_month" class="form-label"
                            >Month</label
                        >
                        <select
                            class="form-select"
                            id="filter_month"
                            name="month"
                            required
                        >
                            <option value="">-- Select Month --</option>
                            <!-- Buat dari 1–12 -->
                            @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter_year" class="form-label">Year</label>
                        <select
                            class="form-select"
                            id="filter_year"
                            name="year"
                            required
                        >
                            <option value="">-- Select Year --</option>
                            @for ($year = 2020; $year <= now()->year; $year++)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</main>
@endsection
