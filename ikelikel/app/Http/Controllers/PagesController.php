<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    // root
    public function dashboard()
    {
        return view('pages.dashboard');
    }
}
