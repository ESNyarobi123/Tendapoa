<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Tumia kwenye route: ->middleware('role:admin') au ->middleware('role:muhitaji|mfanyakazi')
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();
        if (!$user) {
            abort(401); // haja-login
        }

        $allowed = collect(explode('|', $roles))->contains($user->role);
        if (!$allowed) {
            abort(403, 'Huna ruhusa ya ukurasa huu.');
        }

        return $next($request);
    }
}
