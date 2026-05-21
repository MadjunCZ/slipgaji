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
        // Check if user is authenticated
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                ], 401);
            }
            
            return redirect()->route('login');
        }

        // Check if user has admin role (adjust based on your User model)
        $user = $request->user();
        
        // For demo purposes, allow all authenticated users
        // In production, uncomment the role check below:
        // if (!$user->isAdmin()) {
        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Forbidden. Anda tidak memiliki akses ke halaman ini.',
        //         ], 403);
        //     }
        //     
        //     abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        // }

        return $next($request);
    }
}
