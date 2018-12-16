<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 3/3/18
 * Time: 9:50 PM
 */

namespace App\Mail;

use App\Model\User;


class UserMailer extends Mailer
{
    // 测试邮件发送
    public function welcome()
    {
        $user = User::find(2);
        $subject = 'Welcome';
        $view = 'emails.welcome';
        $data = ['name' => $user->name];
        $this->sendTo($user, $subject, $view, $data);
    }

    // 发送身份验证邮件
    public function sendVerifyEmail(User $user)
    {
        $subject = 'ActivateEmail';
        $view = 'emails.auth.verifyEmail';
        $data = ['name' => $user->name, 'code' => $user->mail_code];
        $this->sendTo($user, $subject, $view, $data);
    }

    // 发送激活邮件
    public function sendActivateEmail(User $user)
    {
        $subject = 'ActivateEmail';
        $view = 'emails.auth.activateEmail';
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->token
        ];
        $this->sendTo($user, $subject, $view, $data);
    }
}