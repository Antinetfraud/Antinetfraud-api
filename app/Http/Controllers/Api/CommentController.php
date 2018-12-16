<?php

namespace App\Http\Controllers\Api;

use App\Model\Comment;
use App\Format\CommentFormat;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CommentController extends ApiController
{
    /*
     * 根据'article_id'=id获取文章的列表，
     * pass=1表示已通过管理员审核
     * */
    public function show($id, CommentFormat $commentFormat)
    {
        $comments = Comment::where(['article_id' => $id, 'pass' => 1])->with('user')
            ->latest('created_at')->Paginate(6);
        if ($comments->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $commentFormat->formatCollection($comments->toArray());
            return $this->responseJson(['comments' => $data]);
        }
    }
}
