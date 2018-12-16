<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BrowserHistory extends Model
{
    protected $fillable=['user_id','article_id'];

    public function article()
    {
        return $this->belongsTo('App\Model\Article');
    }
}
