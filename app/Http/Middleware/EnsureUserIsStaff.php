<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $isStaff = $user && (
            $user->hasRole('super_admin') ||
            (!$user->hasRole('customer') && $user->roles()->exists()) ||
            $user->permissions()->exists()
        );

        if (!$isStaff) {
            abort(403, 'Anda tidak memiliki hak akses ke panel admin.');
        }

        return $next($request);
    }
}
