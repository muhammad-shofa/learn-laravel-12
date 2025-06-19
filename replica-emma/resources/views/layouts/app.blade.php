<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- select 2 bootstrap 5 theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- autonumeric --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.8/autoNumeric.js"></script>
    {{-- air datepicker --}}
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.6.0/air-datepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.6.0/air-datepicker.min.css" rel="stylesheet">
    {{-- fullcalender --}}
    <script src=" https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js "></script>
    
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        @include('layouts.navbar')
        @include('layouts.sidebar')

        <!-- Preloader -->
        <div id="preloader" style="position: fixed; top:0; left:0; width:100%; height:100%; background:#fff; z-index:9999; display:flex; align-items:center; justify-content:center;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="content-wrapper p-4 border">
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>

</html>