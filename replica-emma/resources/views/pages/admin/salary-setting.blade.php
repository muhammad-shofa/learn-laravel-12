@extends('layouts.app') @section('title', 'Salary Settings')
@vite(['resources/js/salary-setting.js']) @section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Salary Settings</h3>
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
                Add Salary Setting
            </button>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Salary Settings Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <table class="display nowrap" id="salarySettingTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Employee Code</th>
                                <th>Full Name</th>
                                <th>Position</th>
                                <th>Default Salary</th>
                                <th>Effective Date</th>
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
                class="save-add btn btn-success"
            >
                Save Salary Setting</button
            >'; @endphp
            <x-modal id="addModal" title="Add Salary Setting" :footer="$modalFooter">
                <form id="addSalarySettingForm">
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code</label>
                        <select id="employee_code">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="position_name" class="form-label"
                            >Position</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="position_name"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="base_salary" class="form-label"
                            >Base Salary</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="base_salary"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="default_salary" class="form-label"
                            >Default Salary</label
                        >
                        <br>
                        <i class="text-danger">(default salary cannot be less than the base salary!)</i>

                        <input
                            type="text"
                            class="form-control"
                            id="default_salary"
                            placeholder=""
                            require
                        />
                    </div>
                    <div class="mb-3">
                        <label for="effective_date" class="form-label
                            ">Effective Date</label
                        >
                        <input
                            type="date"
                            class="form-control"
                            id="effective_date"
                            require
                        />
                    </div>

                </form>
            </x-modal>

            {{-- edit modal start --}}
            @php $modalFooter = '<button
                type="button"
                class="save-edit btn btn-success"
            >
                Save Salary Setting</button
            >'; @endphp
            <x-modal id="editModal" title="Edit Salary Setting" :footer="$modalFooter">
                <form id="editSalarySettingForm">
                    <input type="hidden" name="edit_salary_setting_id" id="edit_salary_setting_id">
                    <div class="mb-3">
                        <label for="edit_employee_code" class="form-label">Employee Code</label>
                        <select id="edit_employee_code" disabled>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_position_name" class="form-label"
                            >Position</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="edit_position_name"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="edit_base_salary" class="form-label"
                            >Base Salary</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="edit_base_salary"
                            placeholder=""
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="edit_default_salary" class="form-label"
                            >Default Salary</label
                        >
                        <br>
                        <i class="text-danger">(default salary cannot be less than the base salary!)</i>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_default_salary"
                            placeholder=""
                            require
                        />
                    </div>
                    <div class="mb-3">
                        <label for="edit_effective_date" class="form-label
                            ">Effective Date</label
                        >
                        <input
                            type="date"
                            class="form-control"
                            id="edit_effective_date"
                            require
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
