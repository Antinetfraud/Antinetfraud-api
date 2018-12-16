<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 3/3/18
 * Time: 9:31 PM
 */

namespace App\Mail;


class Mailer
{
    public function sendTo($user,$subject,$view,$data=[])
    {
        \Mail::send($view,$data,function ($message) use ($user,$subject){
           $message->to($user->email)->subject($subject);
        });
    }
}