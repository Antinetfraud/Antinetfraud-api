<?php

namespace App\Http\Controllers\Admin;

use App\Model\Comment;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function all()
    {
        $users = User::latest('created_at')
            ->select(['id', 'name', 'email', 'state', 'created_at', 'updated_at'])->Paginate(9);
        if ($users->isEmpty()) {
            return $this->dataNotFound();
        } else {
            foreach ($users as $user) {
                $user->state_name = $user->getState();
            }
            return $this->responseJson(['users' => $users]);
        }
    }

    public function block($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->dataNotFound();
        } else {
            $user->state = 2;
            $user->update();
            return $this->responseJson();
        }
    }

    public function unblock($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->dataNotFound();
        } else {
            $user->state = 1;
            $user->update();
            return $this->responseJson();
        }
    }

    public function destroy($id)
    {
        $sum = User::where('id', $id)->delete();
        if ($sum == 1) {
            $this->commentDestroy(array($id));
            return $this->responseJson();
        } else {
            return $this->somethingWrong("unknown error");
        }
    }

    public function multipleDestroy(Request $request)
    {
        $idArray = $request->idArray;
        User::whereIn('id', $idArray)->delete();
        $this->commentDestroy($idArray);
        return $this->responseJson();
    }

    //删除用户的时候连带用户的评论也删除
    protected function commentDestroy($array)
    {
        if ($array != null) {
            Comment::whereIn('user_id', $array)->delete();
        }
    }

    // 查询回收站内容
    public function trashed()
    {
        $users = User::onlyTrashed()->Paginate(90);
        if ($users->isEmpty()) {
            return $this->dataNotFound();
        } else {
            foreach ($users as $user) {
                $user->state_name = $user->getState();
            }
            return $this->responseJson(['users' => $users]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        if ($user == null) {
            return $this->dataNotFound();
        } else {
            $user->restore();
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $user = User::withTrashed()->find($id);
        if ($user == null) {
            return $this->dataNotFound();
        } else {
            $user->forceDelete();
            return $this->responseJson();
        }
    }
}
