<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> master
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
<<<<<<< HEAD
=======
        // Check if the user is authenticated
       /*  if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } */
>>>>>>> master
        return $next($request);
    }
}
