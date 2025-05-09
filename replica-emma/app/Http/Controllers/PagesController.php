<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function unauthorized()
    {
        return view('pages.unauthorized.unauthorized');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.dashboard', ['user' => $user]);
        }

        return view('pages.employee.dashboard', ['user' => $user]);
    }

    public function userManagement()
    {
        return view('pages.admin.user-management');
    }

    public function employeeManagement()
    {
        return view('pages.admin.employee-management');
    }

    public function attendance()
    {
        $user = Auth::user();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.attendance', ['user' => $user]);
        }

        return view('pages.employee.attendance', ['user' => $user]);
    }
}
