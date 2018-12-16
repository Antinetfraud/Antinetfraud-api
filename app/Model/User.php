<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use \Illuminate\Auth\Passwords\CanResetPassword;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $rememberTokenName = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'token', 'token_time',
        'state', 'last_ip', 'mail_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function format()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'token' => $this->token,
            'email' => $this->email,
        ];
    }

    public function infoFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function getState()
    {
        if ($this->state == 0) {
            return '邮箱没验证';
        }
        if ($this->state == 1) {
            return '正常用户';
        }
        if ($this->state == 2) {
            return '被封禁';
        }
    }
}
