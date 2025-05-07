<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function dashboard()
    {
        return view('pages/dashboard');
    }

    public function userManagement()
    {
        return view('pages/user-management');
    }

    public function employeeManagement()
    {
        return view('pages/employee-management');
    }
}
