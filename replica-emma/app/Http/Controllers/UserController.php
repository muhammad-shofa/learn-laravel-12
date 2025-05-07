<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        // $data = $this->userModel::all();
        $data = UserModel::with('employee')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function addUser(Request $request)
    {
        // Tambahkan validasi nanti

        // Create a new user
        UserModel::create([
            'employee_id' => $request->input('employee_id'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role')
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }
}
