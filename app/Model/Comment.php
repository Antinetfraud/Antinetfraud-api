<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['article_id', 'user_id', 'content', 'author_reply', 'pass'];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

}
