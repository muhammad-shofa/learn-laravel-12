@extends('layouts.app-employee')


@section('title', 'Salary')
@vite(['resources/js/salary.js'])

@section('content')
<!-- Tambahkan style langsung atau di CSS file -->
<style>
    .time-off-wrapper {
        padding: 0 20px; /* kiri-kanan 20px */
    }

    .card-time-off {
        flex: 1 1 45%; /* fleksibel, lebar sekitar 45% agar muat berdua */
        min-width: 300px; /* supaya tidak terlalu kecil saat layar sempit */
        max-width: 100%;
    }

    @media (max-width: 768px) {
        .card-time-off {
            flex: 1 1 100%; /* jadi satu kolom di layar kecil */
        }
    }
</style>
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm">
                    <h3 class="mb-0">Salary</h3>
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
    {{-- <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            navigation menu
            <x-menu/>
        </div>
    </div> --}}

    <div class="app-content">
        <div class="container-fluid">
            {{-- navigation menu --}}
            <x-menu/>
            <input type="hidden" name="salary_employee_id" id="salary_employee_id" value="{{ $employee->id }}">
    
            <div class="row mt-4">
                <!-- Card: Jam Kerja -->
                <div class="col-md-6">
                    <div class="card text-white mb-3">
                        <div class="card-header bg-primary fw-bold">
                            <h5>
                                Percentage Target Work Duration
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3 flex-wrap justify-align-between">
                                {{-- <div> --}}
                                <div id="salaryAttandanceChart"></div>
                                <div class="text-dark my-5">
                                    <!-- <h3>Target June 2025</h3> -->
                                    <h3>Target {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h3>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td class="p-2 fw-bold">Status</td>
                                                <td class="p-2 fw-bold" style="width: 10px;">:</td>
                                                <td class="p-2" id="statusText"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 fw-bold">Target</td>
                                                <td class="p-2 fw-bold">:</td>
                                                <td class="p-2" id="targetHours"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 fw-bold">Completed</td>
                                                <td class="p-2 fw-bold">:</td>
                                                <td class="p-2" id="completedHours"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 fw-bold">Percentage</td>
                                                <td class="p-2 fw-bold">:</td>
                                                <td class="p-2" id="percentageText"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 fw-bold">Remaining</td>
                                                <td class="p-2 fw-bold">:</td>
                                                <td class="p-2" id="remainingHours"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Card: Cuti -->
                <div class="col-md-6">
                    <div class="card text-white mb-3">
                        <div class="card-header bg-warning fw-bold">
                            <h5>
                                Time Off Taken
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="salaryTimeOffChart"></div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Tabel Gajian -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Salary Histories</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="employeeSalaryTableData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Deduction</th>
                                <th>Bonus</th>
                                <th>Total Salary</th>
                                <th>Payment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script Download CSV -->
    <script>
        // function downloadCSV() {
        //     let table = document.getElementById("salaryTable");
        //     let rows = table.querySelectorAll("tr");
        //     let csv = [];
    
        //     rows.forEach(row => {
        //         let cols = row.querySelectorAll("td, th");
        //         let rowData = [];
        //         cols.forEach(col => rowData.push('"' + col.innerText + '"'));
        //         csv.push(rowData.join(","));
        //     });
    
        //     let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        //     let encodedUri = encodeURI(csvContent);
        //     let link = document.createElement("a");
        //     link.setAttribute("href", encodedUri);
        //     link.setAttribute("download", "riwayat_gaji.csv");
        //     document.body.appendChild(link);
        //     link.click();
        //     document.body.removeChild(link);
        // }
    </script>
    
</main>
@endsection