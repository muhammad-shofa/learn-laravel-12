<?php
namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Session\Middleware\StartSession;

class AutoLogoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Ambil session ID dari cookie
            $sessionId = $request->cookie(config('session.cookie'));

            if ($sessionId) {
                $session = DB::table('sessions')->where('id', $sessionId)->first();

                if ($session) {
                    $lastActivity = Carbon::createFromTimestamp($session->last_activity);
                    $diffInMinutes = now()->diffInMinutes($lastActivity);

                    Log::info([
                        'Session last_activity' => $lastActivity,
                        'Selisih (menit)' => $diffInMinutes,
                    ]);

                    // Misalnya auto logout setelah 1 menit idle
                    if ($diffInMinutes > 1) {
                        // Update is_login ke 0
                        UserModel::where('id', $session->user_id)->update(['is_login' => 0]);

                        // Hapus session
                        DB::table('sessions')->where('id', $sessionId)->delete();
                        Auth::logout();
                        session()->flush();

                        return redirect('/session-expired');
                    }
                }
            }
        }

        return $next($request);
    }
}
