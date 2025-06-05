@extends('layouts.app') @section('title', 'Salary')
@vite(['resources/js/salary.js']) @section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Salary</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <button
                type="button"
                class="btn btn-success my-3"
                data-bs-toggle="modal"
                data-bs-target="#addModal"
            >
                Add Salary
            </button>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Salary Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <table class="display nowrap" id="salaryTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Full Name</th>
                                <th>Position Name</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Deduction</th>
                                <th>Bonus</th>
                                <th>Total Salary</th>
                                <th>Payment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            {{-- position detail modal --}}
            <x-modal id="detailDescriptionModal" title="Description Detail">
                <p id="position-description-field"></p>
            </x-modal>

            {{-- add modal start --}}
            @php $modalFooter = '<button
                type="button"
                class="generate-salary-btn btn btn-success"
            >
                Generate Salary</button
            >'; @endphp
            <x-modal id="addModal" title="Add Salary" :footer="$modalFooter">
                <form id="addSalaryForm">
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code</label>
                        <select id="employee_code">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year" required>
                            <option value="">-- Select Year --</option>
                            @for ($year = 2020; $year <= now()->year; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="month" class="form-label"
                            >Month</label
                        >
                        <select class="form-select" id="month" name="month" required>
                            <option value="">-- Select Month --</option>
                            <!-- Buat dari 1–12 -->
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deduction" class="form-label"
                            >Deduction</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="deduction"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="bonus" class="form-label"
                            >Bonus</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="bonus"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="total_salary" class="form-label"
                            >Total Salary</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="total_salary"
                            placeholder=""
                            disabled
                        />
                    </div>
                </form>
            </x-modal>

               <!-- delete modal start -->
               @php
               $modalFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
               <button type="button" class="confirmed-delete btn btn-danger">Yes</button>';
               @endphp
               <x-modal id="deleteModal" title="Delete Salary Setting" :footer="$modalFooter">
                   <input type="hidden" id="delete_salary_setting_id">
                   <p>Are you sure you want to remove this data?</p>
               </x-modal>
        </div>
    </div>
</main>
@endsection
