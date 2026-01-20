<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAnyRole
{
    /**
     * Allow access if the authenticated user's role is in the allowed list.
     *
     * Usage (without alias):
     * - ->middleware(\App\Http\Middleware\EnsureUserHasAnyRole::class . ':Directie,Magazijnmedewerker')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

