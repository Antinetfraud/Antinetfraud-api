<?php

namespace App\Http\Controllers\Api;

use App\Model\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class FeedbackController extends ApiController
{
    public function store(Request $request)
    {
        $input=$request->all();
        Feedback::create($input);
        return $this->responseJson();
    }
}
