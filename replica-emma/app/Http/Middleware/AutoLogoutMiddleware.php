<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutoLogoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user tidak login secara Laravel
        if (!Auth::check()) {
            // Ambil session_id dari cookie
            $sessionId = $request->cookie(config('session.cookie'));
            $userId = session('user_id');

            // Jika ada session_id dan user_id
            if ($sessionId && $userId) {
                $sessionExists = DB::table('sessions')->where('id', $sessionId)->exists();

                // Jika session tidak ada di database (expired atau terhapus)
                if (!$sessionExists) {
                    // Update is_login ke 0 dan hapus user_id dari session
                    UserModel::where('id', $userId)->update(['is_login' => 0]);
                    session()->forget('user_id');

                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
