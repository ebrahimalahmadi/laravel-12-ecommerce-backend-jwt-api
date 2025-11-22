<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (!auth('admin')->check()) {
        //     return response()->json([
        //         'status'  => false,
        //         'code'    => 401,
        //         'message' => 'Unauthorized!',
        //     ], 401);
        // }

        if (auth('admin')->check()) {
            return $next($request);
        }

        return response()->json([
            'status'  => false,
            'code'    => 401,
            // 'message' => 'Unauthorized Access for Admin !',
            'message' => 'Unauthorized Access denied. Admin privileges required!',
        ], 401);
    }
}
