<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckSessionLogout
{
//  public function handle(Request $request, Closure $next)
// {
//     // Hanya jalankan pengecekan jika route membutuhkan autentikasi
//     $route = $request->route();

//     // Cek apakah middleware auth dipakai oleh route ini
//     if ($route && in_array('auth', $route->middleware() ?? [])) {

//         // Cek session invalid
//         if (!Auth::check()) {
//             $sessionId = $request->cookie(config('session.cookie'));

//             if ($sessionId) {
//                 $session = DB::table('sessions')->where('id', $sessionId)->first();

//                 if ($session && $session->user_id) {
//                     DB::table('users')->where('id', $session->user_id)->update(['is_login' => 0]);
//                     DB::table('sessions')->where('id', $sessionId)->delete();
//                 }
//             }

//             return redirect('/session-expired');
//         }
//     }

//     return $next($request);
// }

// public function handle(Request $request, Closure $next)
// {
//     $route = $request->route();

//     if ($route && in_array('auth', $route->middleware() ?? [])) {
//         // Jika session user tidak ada
//         if (!Auth::check()) {
//             // Coba dapatkan session_id dari cookie
//             $sessionId = $request->cookie(config('session.cookie'));

//             if ($sessionId) {
//                 $session = DB::table('sessions')->where('id', $sessionId)->first();

//                 if ($session && $session->user_id) {
//                     DB::table('users')->where('id', $session->user_id)->update(['is_login' => 0]);
//                     DB::table('sessions')->where('id', $sessionId)->delete();
//                 }
//             }

//             // Tambahan perlindungan — jika Auth::id() ada tapi tidak valid
//             if ($userId = session('user_id')) {
//                 DB::table('users')->where('id', $userId)->update(['is_login' => 0]);
//             }

//             return redirect('/session-expired');
//         }
//     }

//     return $next($request);
// }


}
