<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\ApiController;
use App\Model\Admin;

class LoginController extends ApiController
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/info';

    protected $username = 'name';

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function showInit()
    {
        $admins = Admin::all();
        if ($admins->isEmpty()) {
            return view('admin');
        } else {
            return view('errors.404');
        }
    }

    public function init(Request $request)
    {
        $admins = Admin::all();
        if ($admins->isEmpty()) {
            $input = $request->all();
            Admin::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'level' => 5,
                'remember_token'=>' ',
            ]);

            $data = "管理员初始化成功";
            return $this->responseJson(['data' => $data]);
        } else {
            return $this->dataNotFound();
        }
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth('admin')->user();
            $data = "登录成功";
            return $this->responseJson(['data' => $data, 'user' => $user]);
        }

        $this->setUsername('email');
        $request->email = $request->name;
        if ($this->attemptLogin($request)) {
            $user = Auth('admin')->user();
            $data = "登录成功";
            return $this->responseJson(['data' => $data, 'user' => $user]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        $data = "登录失败，用户名或者密码错误";
        return $this->somethingWrong($data);
    }

    public function logout(Request $request)
    {
        Auth('admin')->logout();
        $request->session()->invalidate();
        $data = "登出成功";
        return $this->responseJson(['data' => $data]);
    }

    public function username()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}
