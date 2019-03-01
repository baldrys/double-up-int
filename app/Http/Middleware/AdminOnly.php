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
        if ($request->user()->role == UserRole::Admin) {
            return $next($request);
        }
        abort(403, "You must be an Admin!");
    }
}
