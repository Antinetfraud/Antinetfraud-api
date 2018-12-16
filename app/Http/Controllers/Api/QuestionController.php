<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Model\Question;
use Illuminate\Support\Facades\DB;

class QuestionController extends ApiController
{
    public function random()
    {
        $questions = Question::orderBy(DB::raw('RAND()'))
            ->take(10)
            ->get();
        dd($questions->toArray());
        $this->responseJson(['questions'=>$questions->toArray()]);
    }
}
