<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Mail\UserMailer;

class AuthController extends ApiController
{
    public function state()
    {
        return $this->responseJson();
    }

    public function info(Request $request)
    {
        $user = User::find($request->id);
        if ($user == null) {
            return $this->dataNotFound();
        }
        return $this->responseJson(['user' => $user->infoFormat()]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        //dd($request->all());
        $user = User::find($request->id);
        //dd($user);
        if ($user == null) {
            return $this->somethingWrong("登出失败");
        }
        $user->token = NULL;
        $user->save();
        return $this->responseJson();
    }

    // 激活邮箱
    public function activateEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {

            if ($user->token == $request->token) {
                if ($user->state == 0) {
                    $user->state = 1;
                    $user->mail_code = '0';
                    $user->update();
                    return view('activateEmailSuccess');
                } else {
                    return view('errors.message', ['message' => 'user state is wrong']);
                }

            } else {
                return view('errors.message', ['message' => 'user token is wrong']);
            }
        } else {
            return view('errors.message', ['message' => 'user is null']);
        }

    }

    // 发送身份验证邮件
    public function sendVerifyEmail(Request $request, UserMailer $userMailer)
    {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if ($user->name != $request->name) {
                return $this->somethingWrong($user->name." ".$request->name."邮箱和用户名不匹配");
            } else {
                $user->mail_code = substr(strval(rand(10000, 19999)), 1, 4);
                $user->update();
                $userMailer->sendVerifyEmail($user);
                return $this->responseJson();
            }
        } else {
            return $this->somethingWrong("该邮箱的用户不存在");
        }
    }

    //发送邮箱激活邮件
    public function sendActivateEmail(Request $request, UserMailer $userMailer)
    {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if ($user->state == 0) {
                $userMailer->sendActivateEmail($user);
                return $this->responseJson();
            } else {
                return $this->somethingWrong("该用户已激活，或已被封禁");
            }

        } else {
            return $this->somethingWrong("该邮箱没有被注册");
        }
    }
}
