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
                    'is_login' => 0,
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

            // Cek apakah akun sedang login di tempat lain
            if ($user->is_login == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'This account is already active in another session.'
                ], 200);
            }

            // Jika password benar
            if (password_verify($request->password, $user->password)) {
                $user->update([
                    'try_login' => 5,
                    'status_login' => 'active',
                    'is_login' => 1,
                    'cooldown_until' => null
                ]);

                Auth::login($user);
                session()->put('user_id', $user->id);

                // session([
                //     'user_id' => $user->id,
                // ]);

                // Simpan juga ke cookie selama waktu
                cookie()->queue(cookie('user_id_temp', $user->id, 120));

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
    }

    public function logoutAuth()
    {
        if (Auth::check()) {
            // Ambil user yang sedang login
            $user = userModel::find(Auth::id());

            // Ubah is_login jadi 0
            if ($user) {
                $user->is_login = 0;
                $user->save();
            }

            // Logout user dan hapus session
            Auth::logout();
            session()->flush();
        }

        return redirect('/')->with('message', 'Logout successful');
    }
}
