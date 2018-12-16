<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth('admin')->check()) {
            return $next($request);
        } else {
            $data['code'] = 401;
            $data['message'] = 'unauthorized';
            return response()->json($data);
        }
    }
}
