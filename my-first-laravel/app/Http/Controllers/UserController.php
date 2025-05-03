<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function getUsers()
    {
        $users = $this->users->all();
        return response()->json($users);
    }
}
