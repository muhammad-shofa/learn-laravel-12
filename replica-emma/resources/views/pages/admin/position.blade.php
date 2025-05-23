@extends('layouts.app')

@section('title', 'Position')
@vite(['resources/js/position.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Position</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <button type="button" class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Position</button>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Position Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <table class="display nowrap" id="positionTableData">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Position Name</th>
                                <th>Description</th>
                                <th>Hourly Rate</th>
                                <th>Annual Salary Increase</th>
                                <th>Base Salary</th>
                                <th>Status</th>
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
            @php
            $modalFooter = '<button type="button" class="save-add btn btn-success">Save User</button>';
            @endphp
            <x-modal id="addModal" title="Add Position" :footer="$modalFooter">
                <form id="addPositionForm">
                    <div class="mb-3">
                        <label for="position_name" class="form-label">Position Name</label>
                        <input type="text" class="form-control" id="position_name" require />
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3" require></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="hourly_rate" class="form-label">Hourly Rate</label>
                        <input type="number" class="form-control" id="hourly_rate" placeholder="" require />
                    </div>
                    <div class="mb-3">
                        <label for="annual_salary_increase" class="form-label">Annual Salary Increase</label>
                        <input type="number" class="form-control" id="annual_salary_increase" require />
                    </div>
                    <div class="mb-3">
                        <label for="base_salary" class="form-label">Base Salary</label>
                        <input type="number" class="form-control" id="base_salary" require />
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" require>
                            <option selected value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</main>
@endsection
