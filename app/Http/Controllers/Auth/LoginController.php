<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $username = 'name';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }


    /**
     *Set the login username to be used by the controller.
     *
     * @param  string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $haveCode = False;
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($request->mail_code != '') {
            $haveCode = True;
        }
        $user = User::where('name', $request->name)->first();
        if ($user == null) {
            return $this->somethingWrong("没有该用户");
        }

//        if ($haveCode == True) {
//            // 有邮箱验证码，不校验登录IP
//            if ($user->mail_code == '0') {
//                return $this->somethingWrong('邮箱验证码过期，请重新申请');
//            }
//            if ($user->mail_code != $request->mail_code) {
//                // 邮箱验证码只能使用一次，无论正确与否，都作废
//                $user->mail_code = '0';
//                $user->update();
//                return $this->somethingWrong('邮箱验证码不符！');
//            } else {
//                $user->mail_code = '0';
//                $user->update();
//            }
//        } else {
//            // 检验IP是否是常用地IP，不是常用IP登录，返回异常
//            $clientIp = $request->getClientIp();
//            if ($user->last_ip == '127.0.0.1') {
//
//            } else {
//                $userIpArray = explode(".", $user->last_ip);
//                $clientIpArray = explode(".", $clientIp);
//                if ($userIpArray[0] == $clientIpArray[0] &&
//                    $userIpArray[1] == $clientIpArray[1]) {
//
//                } else {
//                    return $this->AuthIPWrong('不是常用地登录，请通过邮箱验证身份');
//                }
//
//            }
//        }
//
//        if ($user->state == 0) {
//            return $this->AuthWrong("用户邮箱没激活");
//        }

        if ($user->state == 2) {
            return $this->somethingWrong("用户已被封禁");
        }

        if ($this->attemptLogin($request)) {
            $user = $this->loginSuccessed();
            $user->last_ip = $request->getClientIp();
            $user->update();
            return $this->responseJson(['user' => $user->format()]);
        }

        $this->setUsername('email');
        $request->email = $request->name;
        if ($this->attemptLogin($request)) {
            $user = $this->loginSuccessed();
            $user->last_ip = $request->getClientIp();
            $user->update();
            return $this->responseJson(['user' => $user->format()]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        //return $this->sendFailedLoginResponse($request);
        return $this->somethingWrong("登录失败，用户名和密码不匹配");
    }

    protected function loginSuccessed()
    {
        $token = auth()->user()->username . Carbon::now() . auth()->user()->email . time();
        auth()->user()->token = bcrypt($token);
        auth()->user()->token_time = time();
        auth()->user()->save();
        $data = auth()->user();
        return $data;
    }


}
