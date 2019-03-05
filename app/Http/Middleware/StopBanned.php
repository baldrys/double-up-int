<?php

namespace App\Http\Middleware;

use Closure;

class StopBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->banned) {
            return $next($request);
        }
        return response()->json([
            "success" => false,
            "message" => "Your account was banned!",
        ], 403);
    }
}
