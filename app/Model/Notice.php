<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable=['title','content'];

    public function admin()
    {
        return $this->belongsTo('App\Model\Admin');
    }
}
