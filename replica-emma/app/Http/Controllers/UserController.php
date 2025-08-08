<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // get all users data
    public function getUsers()
    {
        $data = UserModel::with('employee')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // get user by id
    public function getUser($id)
    {
        $data = UserModel::with('employee')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // add new user
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'username' => 'required|string|unique:users,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'role' => 'required|string',
        ], [
            'password.regex' => 'Passwords must consist of uppercase letters, lowercase letters, numbers, and symbols.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $employee_id = $request->input('employee_id');

        // Create a new user
        UserModel::create([
            'employee_id' => $employee_id,
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role')
        ]);

        if (isset($employee_id)) {   
            // update has_account di table employees katika berhasil menambahkan akun employee
            $employee = EmployeeModel::findOrFail($employee_id);
            $employee->update([
                'has_account' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully'
        ], 201);
    }

    // update user
    public function updateUser(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);

        $user->update([
            'username' => $request->input('username'),
            'role' => $request->input('role'),
            'try_login' => $request->input('try_login'),
            'status_login' => $request->input('status_login'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
    }

    // reset user password
    public function updateUserPassword(Request $request, $user_id)
    {
        $user = UserModel::findOrFail($user_id);

        // Validate the new password
        $request->validate([
            'password' => 'required|min:6',
        ]);

        // Update the user's password
        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ], 200);
    }

    // delete user
    public function deleteUser($id)
    {
        $user = UserModel::findOrFail($id);
        $employee = EmployeeModel::findOrFail($user->employee_id);
        // Set has_account to 0 in the employee table
        $employee->update([
            'has_account' => 0,
        ]);

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
}
