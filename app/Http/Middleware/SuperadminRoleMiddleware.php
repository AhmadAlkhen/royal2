<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperadminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) 
        return response(null, 403);

        $user = Auth::user()->type;


        if($user ==='superAdmin'){
            return $next($request);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => "you don't have permission to access",
            ], 403);
        }
    }
}
