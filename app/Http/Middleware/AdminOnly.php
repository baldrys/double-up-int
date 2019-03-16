<?php

namespace App\Http\Middleware;

use App\Support\Enums\UserRole;
use Closure;

class AdminOnly
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
        $me = auth("api")->user();
        if ($me->role == UserRole::Admin) {
            return $next($request);
        }
        return response()->json([
            "success" => false,
            "message" => "You must be an Admin!",
        ], 403);
    }
}
