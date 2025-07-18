<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIdIsZeroAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check() && auth('sanctum')->user()->role_id == 0) {
            return $next($request);
        }

        return response()->json([
            'message' => 'You are not authorized to access this resource.',
            'status' => 'error'
        ], 403);  // 403 Forbidden
    }
}
