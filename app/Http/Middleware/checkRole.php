<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {

        $user = $request->user();
        if (! $user || ! in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
