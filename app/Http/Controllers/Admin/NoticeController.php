<?php

namespace App\Http\Controllers\Admin;

use App\Model\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Parsedown;

class NoticeController extends ApiController
{
    public function all()
    {
        $notices = Notice::latest('created_at')
            ->select(['id', 'title', 'created_at', 'updated_at', 'admin_id'])
            ->withOnly('admin', ['name'])
            ->paginate(6);
        if ($notices->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['notices' => $notices]);
        }
    }

    public function show($id)
    {
        $notice = Notice::find($id);
        if ($notice == null) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['notice' => $notice]);
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Notice::create($input);
        return $this->responseJson();
    }

    public function update($id, Request $request)
    {
        $input = $request->all();
        $notice = Notice::find($id);
        $notice->update($input);
        return $this->responseJson();
    }

    public function destroy($id)
    {
        $notice = Notice::find($id);
        if ($notice == null) {
            return $this->dataNotFound();
        } else {
            $notice->delete();
            return $this->responseJson();
        }
    }

    public function multipleDestroy(Request $request)
    {
        $idArray = $request->idArray;
        Notice::whereIn('id', $idArray)->delete();
        return $this->responseJson();
    }

    // 查询回收站内容
    public function trashed()
    {
        $notices = Notice::onlyTrashed()->Paginate(90);
        if ($notices->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['notices' => $notices]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $notice = Notice::withTrashed()->find($id);
        if ($notice == null) {
            return $this->dataNotFound();
        } else {
            $notice->restore();
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $notice = Notice::withTrashed()->find($id);
        if ($notice == null) {
            return $this->dataNotFound();
        } else {
            $notice->forceDelete();
            return $this->responseJson();
        }
    }
}
