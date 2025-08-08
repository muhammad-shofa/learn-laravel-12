<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\PositionModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    // unauthorized
    public function unauthorized()
    {
        // Ambil dari session ATAU dari cookie
        $userId = session('user_id') ?? request()->cookie('user_id_temp');

        if ($userId) {
            UserModel::where('id', $userId)->update(['is_login' => 0]);

            session()->forget('user_id');
            cookie()->queue(cookie()->forget('user_id_temp'));
        }
        
        return view('pages.unauthorized.unauthorized');
    }

    // login
    public function login()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    // dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $employee = EmployeeModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.dashboard', ['user' => $user]);
        }

        return view('pages.employee.dashboard', ['user' => $user, 'employee' => $employee]);
    }

    // user management
    public function userManagement()
    {
        return view('pages.admin.user-management');
    }

    public function employeeManagement()
    {
        return view('pages.admin.employee-management');
    }

    // attendance
    public function attendance()
    {
        $user = Auth::user();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.attendance', ['user' => $user]);
        }

        return view('pages.employee.attendance', ['user' => $user]);
    }

    // time off
    public function timeOff()
    {
        $user = Auth::user();
        $employee = EmployeeModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.time-off', ['user' => $user]);
        }

        return view('pages.employee.time-off', ['user' => $user, 'employee' => $employee]);
    }

    // position
    public function position()
    {
        $user = Auth::user();
        // $employee = PositionModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        // if ($user->role == 'admin') {
        return view('pages.admin.position', ['user' => $user]);
        // }

        // return view('pages.employee.position', ['user' => $user]);
    }

    // salary setting
    public function salarySetting()
    {
        $user = Auth::user();
        // $employee = PositionModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        // if ($user->role == 'admin') {
        return view('pages.admin.salary-setting', ['user' => $user]);
        // }

        // return view('pages.employee.salary-setting', ['user' => $user]);
    }

    // salary
    public function salary()
    {
        $user = Auth::user();
        $employee = EmployeeModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.salary', ['user' => $user]);
        }

        return view('pages.employee.salary', ['user' => $user, 'employee' => $employee]);
    }

    // report
    public function report()
    {
        $user = Auth::user();
        $employee = EmployeeModel::where('id', $user->employee_id)->first();

        // Check if the user role is an admin
        if ($user->role == 'admin') {
            return view('pages.admin.report', ['user' => $user]);
        }

        return view('pages.employee.report', ['user' => $user, 'employee' => $employee]);
    }
}
