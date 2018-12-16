<?php

namespace App\Http\Controllers\Auth;

use App\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CreateCommentRequest;

class CommentController extends ApiController
{
    public function store(CreateCommentRequest $request)
    {
        $input = $request->all();
        /*
        * 因为最初的设计缺陷，用id表示user_id。
        * 然后在这个API里面，需要把id改成user_id并把id unset掉。
        * 因为comment表的id是自增的。
        * */
        $input['user_id'] = $input['id'];
        unset($input['id']);

        $input['author_reply'] = null;
        Comment::create($input);
        return $this->responseJson();
    }
}
