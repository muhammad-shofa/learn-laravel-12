<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function login()
    {
        return view('pages/auth/login');
    }

    public function register()
    {
        return view('pages/auth/register');
    }

    public function dashboard()
    {
        return view('pages/dashboard');
    }
}
