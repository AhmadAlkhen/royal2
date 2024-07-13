<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuth
{
 
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check())
        return response(null, 403);

        if(!Auth::user()->status)
            return response()->json([
                'status' => false,
                'message' => "Your account has been disabled"
            ], 401);

        $token = $request->bearerToken();
        $remember_token = Auth::user()->remember_token;

        if($token !== $remember_token)
            return response()->json([
                'status' => false,
                'message' => "Unauthorized"
            ], 401);
    

       return $next($request);
    }
}
