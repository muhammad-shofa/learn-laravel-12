<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emma | Employee Management</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
</head>

<body class="login-page">
    <!-- Preloader -->
    <div id="preloader" style="position: fixed; top:0; left:0; width:100%; height:100%; background:#fff; z-index:9999; display:flex; align-items:center; justify-content:center;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h1 class="mb-2 text-center">Emma</h1>
            <p class="text-center mb-0">Please login before access your dashboard!</p>
        </div>
        <div class="card-body login-card-body py-5">
            <form id="loginForm" method="post">
                <div class="input-group mb-3">
                    <div class="input-group-text"><span class="fa-solid fa-user"></span></div>
                    <div class="form-floating">
                        <input type="text" id="username" name="username" class="form-control" value="" placeholder="" />
                        <label for="username">Username</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-text"><span class="fa-solid fa-key"></span></div>
                    <div class="form-floating">
                        <input type="password" id="password" name="password" class="form-control" placeholder="" />
                        <label for="password">Password</label>
                    </div>
                </div>
                <!--begin::Row-->
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!--end::Row-->
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>

</body>

</html>