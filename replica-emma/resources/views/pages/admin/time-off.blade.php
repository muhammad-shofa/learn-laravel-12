@extends('layouts.app')

@section('title', 'Time Off')
@vite(['resources/js/time-off.js'])

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Time Off</h3>
                    
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
                        <h3 class="card-title">Time Off Requests Table</h3>
                        <button class="btn btn-danger" id="btnExportTimeOff">Export <i class="fa-solid fa-file-lines"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 px-4">
                    <div class="table-responsive">
                        <table class="display nowrap" id="timeOffTableData">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Employee Code</th>
                                    <th>Full Name</th>
                                    <th>Request Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            {{-- time off reason detail --}}
            <x-modal id="detailReasonModal" title="Reason Detail">
                <p id="time-off-reason-field"></p>
            </x-modal>
        </div>
    </div>
</main>
@endsection
