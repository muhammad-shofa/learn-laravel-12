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

    public function loginAuth(Request $request)
    {

        // Validate the request data
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Authenticate the user
        $user = $this->userModel->where('username', $validated['username'])->first();

        if ($user && password_verify($validated['password'], $user->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password',
            ], 401);
        }
    }
}
