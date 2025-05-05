<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AdminLTE 4 + Laravel 12</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    @include('components.navbar')
    @include('components.sidebar')

    <div class="content-wrapper p-4">
        @yield('content')
    </div>

</body>

</html>