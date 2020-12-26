<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\UserMailer;

class RegisterController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Model\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'token' => bcrypt($data['name'] . Carbon::now() . $data['password'] . $data['email'] . time()),
            'token_time' => time(),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, UserMailer $userMailer)
    {
        $this->validator($request->all())->validate();
        event(new Registered($userRegister = $this->create($request->all())));
        $this->guard()->login($userRegister);
        $user = Auth()->user();
//        $userMailer->sendActivateEmail($user);
        return $this->registered($request, $userRegister)
            ? $this->responseJson()
            : $this->somethingWrong("注册失败");
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return $user->name == $request->name ? true : false;
    }

    public function checkEmail(Request $request)
    {
        if ($request->email == null) {
            return $this->somethingWrong("邮箱字段不能为空");
        }
        $user = User::where('email', $request->email)->get();
        if (!$user->isEmpty()) {
            return $this->somethingWrong("邮箱已被注册");
        } else {
            return $this->responseJson();
        }
    }

    public function checkName(Request $request)
    {
        if ($request->name == null) {
            return $this->somethingWrong("用户名字段不能为空");
        }
        $user = User::where('name', $request->name)->get();
        if (!$user->isEmpty()) {
            return $this->somethingWrong("用户名已被注册");
        } else {
            return $this->responseJson();
        }
    }

}
