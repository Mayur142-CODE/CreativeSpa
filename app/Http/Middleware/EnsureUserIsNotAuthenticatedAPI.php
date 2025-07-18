<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotAuthenticatedAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated via Sanctum
        if (auth('sanctum')->check()) {
            return response()->json([
                'message' => 'You are already logged in.',
                'redirect' => url('admin/dashboard')
            ], 403);  // 403 Forbidden
        }

        return $next($request);
    }
}
