<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginAuth(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if the user exists and the password is correct
        $user = $this->userModel::where('username', $request->username)->first();

        if ($user && password_verify($request->password, $user->password)) {

            // save user data to session
            // session(['user' => $user]);
            Auth::login($user);
            // Authentication passed
            return response()->json(['success' => true, 'message' => 'Login successful'], 200);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }
}
