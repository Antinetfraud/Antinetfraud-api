<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable=['title','optionA','optionB','optionC','optionD','answer','type'];

    protected $dates = ['deleted_at'];
}
