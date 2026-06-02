<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! $request->user()->staff) {
            abort(403, 'Unauthorized. Access restricted to Staff only.');
        }

        // Redirect to pending approval page if status is not active
        // Skip check if already on the pending page
        if ($request->user()->staff->role == 'anggota-kader' && $request->user()->staff->status !== 'active' && ! $request->routeIs('pending.approval')) {
            return redirect()->route('pending.approval');
        }

        if (in_array($request->user()->staff->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Role not allowed.');
    }
}
