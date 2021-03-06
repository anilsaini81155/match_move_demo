<?php

namespace App\Http\Middleware;

use App\Contracts\AuthCheck;
use Illuminate\Http\Request;
use Closure;

class AuthenticateAdminRequest
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
        
        return $next($request);
    }
}
