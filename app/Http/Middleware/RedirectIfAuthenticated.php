<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('web')->check()) {
            return redirect('/home');
        }else if(Auth('admin')->check()){
            return response()->json(['code'=>500,'message'=>"你已经登录了！"]);
        }

        return $next($request);
    }
}
