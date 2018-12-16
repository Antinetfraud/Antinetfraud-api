<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable=['title','image','source','content','tag_id','praise','reading'];

    protected $dates = ['deleted_at'];

    public function tag()
    {
        return $this->belongsTo('App\Model\Tag');
    }
}
