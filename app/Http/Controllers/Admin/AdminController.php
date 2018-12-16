<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\Model\Admin;

class AdminController extends ApiController
{
    public function state()
    {
        return $this->responseJson();
    }

    public function all()
    {
        $admins = Admin::latest('created_at')
            ->select(['id', 'name', 'email', 'level', 'created_at', 'updated_at'])
            ->Paginate(9);
        if ($admins->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['admins' => $admins]);
        }
    }

    public function resetPassword(Request $request)
    {
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $id = auth('admin')->user()['id'];
        $admin = Admin::find($id);
        if (\Hash::check($oldPassword, $admin->password)) {
            $admin->password = bcrypt($newPassword);
            $admin->save();
            Auth('admin')->logout();
            $request->session()->invalidate();
            return $this->responseJson();
        } else {
            return $this->somethingWrong("旧密码不匹配，重置失败");
        }

    }

    public function create(Request $request)
    {
        $input = $request->all();
        $level = auth('admin')->user()['level'];
        if ($level == 0) {
            return $this->somethingWrong("权限不足");
        }
        if ($level < $input['level']) {
            return $this->somethingWrong("权限不足");
        } else {
            Admin::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'level' => $input['level'],
                'remember_token' => ' ',
            ]);
            return $this->responseJson();
        }
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        $level = auth('admin')->user()['level'];
        if ($level < $admin->level) {
            return $this->somethingWrong("权限不足，你无法删除比你高级的管理员");
        } else {
            $admin->delete();
            return $this->responseJson();
        }
    }

    // 查询回收站内容
    public function trashed()
    {
        $admins = Admin::onlyTrashed()->Paginate(90);
        if ($admins->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['admins' => $admins]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $admin = Admin::withTrashed()->find($id);
        if ($admin == null) {
            return $this->dataNotFound();
        } else {
            $admin->restore();
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $admin = Admin::withTrashed()->find($id);
        $level = auth('admin')->user()['level'];

        if ($admin == null) {
            return $this->dataNotFound();
        } else if ($level < $admin->level) {
            return $this->somethingWrong("权限不足，你无法删除比你高级的管理员");
        } else {
            $admin->forceDelete();
            return $this->responseJson();
        }
    }

}
