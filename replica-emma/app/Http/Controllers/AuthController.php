<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function loginAuth(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if the user exists and the password is correct
        $user = userModel::where('username', $request->username)->first();

        if ($user) {
            // cek apakah akun user sedang nonactive dan waktu saat ini lebih besar dari cooldown_until (cooldown selesai)
            if ($user->status_login === 'nonactive' && now()->greaterThan($user->cooldown_until)) {
                $user->update([
                    'try_login' => 5,
                    'status_login' => 'active',
                    'cooldown_until' => null
                ]);
            }

            // Cek apakah akun user sedang cooldown
            if ($user->cooldown_until && now()->lessThan($user->cooldown_until)) {
                $wait = ceil(now()->diffInMinutes($user->cooldown_until));
                return response()->json([
                    'success' => false,
                    'message' => "Account locked. Please try again after {$wait} minutes."
                ], 200);
            }

            // Jika password benar
            if (password_verify($request->password, $user->password)) {
                $user->update([
                    'try_login' => 5,
                    'status_login' => 'active',
                    'cooldown_until' => null
                ]);
                Auth::login($user);

                return response()->json(['success' => true, 'message' => 'Login success'], 200);
            } else {
                // Jika password salah
                $decreaseTryLogin = $user->try_login - 1;
                $status_login = 'active';
                $cooldown = null;

                if ($decreaseTryLogin <= 0) {
                    $status_login = 'nonactive';
                    $cooldown = now()->addMinutes(3); // Cooldown 5 menit
                }

                $user->update([
                    'try_login' => max($decreaseTryLogin, 0),
                    'status_login' => $status_login,
                    'cooldown_until' => $cooldown
                ]);

                $message = $decreaseTryLogin <= 0
                    ? "Account locked. Please try again after 3 minutes."
                    : "Incorrect username or password. Change left: {$decreaseTryLogin}";

                return response()->json(['success' => false, 'message' => $message], 200);
            }
        }

        return response()->json(['success' => false, 'message' => 'Incorrect username or password'], 200);




        // OLD OLD OLD OLD OLD
        // if ($user && password_verify($request->password, $user->password)) {

        //     // save user data to session
        //     // session(['user' => $user]);
        //     Auth::login($user);
        //     // Authentication passed
        //     return response()->json(['success' => true, 'message' => 'Login successful'], 200);
        // } else {
        //     $tmpUsername = UserModel::where('username', $request->username)->first();
        //     $tryLogin = $tmpUsername->try_login;
        //     $decreaseTryLogin = $tryLogin - 1;
        //     $status_login = "active";
        //     $textChangeLeft = $decreaseTryLogin . " change left";
        //     $wait = "";

        //     // $decreaseTryLogin <= 0 ? $status_login = "nonactive" : $status_login = "active";

        //     if ($decreaseTryLogin <= 0) {
        //         $status_login = "nonactive";
        //         $wait = "5 Minutes";
        //     }

        //     $tmpUsername->update([
        //         'try_login' => $decreaseTryLogin,
        //         'status_login' => $status_login
        //     ]);

        //     return response()->json(['success' => false, 'message' => 'Incorrect username or password, ' . $textChangeLeft . " " . $wait], 200);
        // }

        // return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }

    public function logoutAuth()
    {
        // Logout the user
        Auth::logout();

        // Clear the session
        session()->flush();

        // Redirect to the login page
        return redirect('/')->with('message', 'Logout successful');
    }
}
