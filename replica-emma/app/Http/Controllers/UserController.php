<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function addUser()
    {
        // if (userModel::create($request)) {
        //     return response()->json(['message' => 'User created successfully'], 201);
        // }
        // Validate the request
        // $request->validate([
        //     'employee_id' => 'required|exists:employees,id',
        //     'username' => 'required|string|max:255|unique:users,username',
        //     'password' => 'required|string|min:8',
        //     'role' => 'required|in:admin,employee',
        // ]);

        // Create a new user
        $user = $this->userModel;
        $user->username = 'admin';
        $user->password = bcrypt('admin123');
        $user->try_login = 5; // Default value
        $user->status_login = 'active'; // Default value
        $user->role = 'admin';
        $user->save();

        return response()->json(['message' => 'User created successfully'], 201);
    }
}
