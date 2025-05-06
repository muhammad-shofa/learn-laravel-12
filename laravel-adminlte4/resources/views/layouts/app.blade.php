<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AdminLTE 4 + Laravel 12</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        @include('layouts.navbar')
        @include('layouts.sidebar')

        <div class="content-wrapper p-4 border">
            @yield('content')
        </div>
    </div>

</body>

</html>