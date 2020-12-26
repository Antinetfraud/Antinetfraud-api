<?php

namespace App\Http\Middleware;

use App\Model\User;
use Closure;
use Illuminate\Support\Facades\Log;

class ApiAuthMiddleware
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
        $path = $request->path();
        $user = User::find($request->id);
        if ($user == null) {
            return $this->unauthorized("user not found");
//        } else if ($user->state == 0) {
//            return $this->unauthorized("用户邮箱没激活");
        } else if ($user->state == 2) {
            return $this->unauthorized("用户被封禁");
        } else if ($user->token == null || $user->token_time - time() > 172800) {
            return $this->unauthorized("user token null or token_time timeout");
        } else if (time() - $request->timestamp > 300) {
            return $this->unauthorized("timestamp timeout");
        } else if ($request->sign == md5($user->token . $request->timestamp . $path)) {
            return $next($request);
        } else if ($request->sign == $user->token){
            # 临时增加的校验，给app使用
            return $next($request);
        } else {
            return $this->unauthorized("sign not match");
        }
    }

    protected function unauthorized($info)
    {
        $data['code'] = 401;
        $data['message'] = 'unauthorized'." ".$info;
//        $data['info'] = $info;
        return response()->json($data);
    }
}
