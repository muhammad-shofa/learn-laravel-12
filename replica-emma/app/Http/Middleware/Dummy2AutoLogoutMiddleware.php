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
        if (!Auth::check()) {
            // Ambil session_id dari cookie
            $sessionId = $request->cookie(config('session.cookie'));
            $userId = session('user_id');

            if ($userId && $sessionId) {
                // Cek apakah session masih ada di database
                $exists = DB::table('sessions')->where('id', $sessionId)->exists();

                if (!$exists) {
                    // session expired → update is_login ke 0
                    UserModel::where('id', $userId)->update(['is_login' => 0]);
                    session()->forget('user_id');

                    return redirect()->to('/');
                }
            }
        }

        return $next($request);
    }
}
