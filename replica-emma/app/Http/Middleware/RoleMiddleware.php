<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        

        // Jika tidak login atau role-nya tidak termasuk dalam yang diizinkan
        if (!$user || !in_array($user->role, $roles)) {
            return redirect('/unauthorized');
        }

        return $next($request);
    }
}
