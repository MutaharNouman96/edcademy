<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Backwards-compatible: support both the legacy `users.role` column
        // and Spatie's roles system (if present).
        $allowedByLegacyRole = in_array($user->role, $roles, true);
        $allowedBySpatieRole = method_exists($user, 'hasAnyRole') ? $user->hasAnyRole($roles) : false;

        if (!$allowedByLegacyRole && !$allowedBySpatieRole) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
