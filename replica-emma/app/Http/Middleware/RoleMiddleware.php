<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // Jangan ganggu route yang sudah kita atur
        if ($request->is('/') || $request->is('session-expired') || $request->is('unauthorized')) {
            return $next($request);
        }

        // Jika session habis dan user sudah logout otomatis
        // if (!Auth::check()) {
        //     $userId = session('user_id');

        //     // Update is_login ke 0 dan hapus user_id dari session
        //     UserModel::where('id', $userId)->update(['is_login' => 0]);
        //     session()->forget('user_id');

        //     return redirect('/');
        // }
        // if (!Auth::check()) {
        //     Log::info('Session expired detected');

        //     $sessionId = Session::getId();
        //     Log::info('Session ID:', ['id' => $sessionId]);

        //     $sessionData = DB::table('sessions')->where('id', $sessionId)->first();
        //     Log::info('Session data:', ['data' => $sessionData]);

        //     if ($sessionData && $sessionData->payload) {
        //         $payload = unserialize(base64_decode($sessionData->payload));
        //         $userId = $payload['user_id'] ?? null;

        //         if ($userId) {
        //             UserModel::where('id', $userId)->update(['is_login' => 0]);
        //         }
        //     }

        //     Session::forget('user_id');
        //     return redirect('/');
        // }

        // Jika session sudah tidak valid
        if (!Auth::check()) {
            Log::info('Session expired detected');

            $sessionId = Session::getId();
            Log::info('Session ID:', ['id' => $sessionId]);

            $userId = null;

            // Ambil dari session database
            $sessionData = DB::table('sessions')->where('id', $sessionId)->first();
            if ($sessionData && $sessionData->payload) {
                $payload = unserialize(base64_decode($sessionData->payload));
                $userId = $payload['user_id'] ?? null;
                Log::info('User ID from session payload', ['user_id' => $userId]);
            }

            // Kalau gagal, coba ambil dari cookie cadangan
            if (!$userId) {
                $userId = $request->cookie('user_id_temp');
                Log::info('User ID from cookie fallback', ['user_id' => $userId]);
            }

            if ($userId) {
                DB::table('users')->where('id', $userId)->update(['is_login' => 0]);
            }

            Session::forget('user_id');
            return redirect('/');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            return redirect('/unauthorized');
        }

        return $next($request);
    }
}
