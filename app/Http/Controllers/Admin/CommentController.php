<?php

namespace App\Http\Controllers\Admin;

use App\Model\Comment;
use App\Format\Admin\CommentFormat;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CommentController extends ApiController
{
    // 获取评论列表
    public function all(CommentFormat $commentFormat)
    {
        $comments = Comment::latest('created_at')->with('user')->Paginate(9);
        if ($comments->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $commentFormat->formatCollection($comments->toArray());
            return $this->responseJson(['comments' => $data]);
        }
    }

    // 删除
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment == null) {
            return $this->dataNotFound();
        } else {
            $comment->delete();
            return $this->responseJson();
        }
    }

    // 批量删除
    public function multipleDestroy(Request $request)
    {
        $idArray = $request->idArray;
        Comment::whereIn('id', $idArray)->delete();
        return $this->responseJson();
    }

    // 作者回复
    public function reply(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->author_reply = $request->author_reply;
        $comment->update();
        return $this->responseJson();
    }

    // 通过评论
    public function pass($id)
    {
        $comment = Comment::find($id);
        $comment->pass = 1;
        $comment->update();
        return $this->responseJson();
    }

    // 把评论状态设置为不通过
    public function block($id)
    {
        $comment = Comment::find($id);
        $comment->pass = 0;
        $comment->update();
        return $this->responseJson();
    }

    // 查询回收站内容
    public function trashed(CommentFormat $commentFormat)
    {
        $comments = Comment::onlyTrashed()->with('user')->Paginate(90);
        if ($comments->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $commentFormat->formatCollection($comments->toArray());
            return $this->responseJson(['comments' => $data]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $comment = Comment::withTrashed()->find($id);
        if ($comment == null) {
            return $this->dataNotFound();
        } else {
            $comment->restore();
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $comment = Comment::withTrashed()->find($id);
        if ($comment == null) {
            return $this->dataNotFound();
        } else {
            $comment->forceDelete();
            return $this->responseJson();
        }
    }
}
