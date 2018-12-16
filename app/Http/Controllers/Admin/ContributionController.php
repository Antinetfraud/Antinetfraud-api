<?php

namespace App\Http\Controllers\Admin;

use App\Model\Contribution;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Format\Admin\ContributionFormat;

class ContributionController extends ApiController
{
    // 显示列表
    public function all(ContributionFormat $contributionForamt)
    {
        $contributions = Contribution::latest('created_at')->paginate(10);
        if ($contributions->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $contributionForamt->formatCollection($contributions->toArray());
            return $this->responseJson(['contributions' => $data]);
        }
    }

    // 显示详细内容
    public function show($id)
    {
        $contribution = Contribution::find($id);
        if ($contribution == null) {
            return $this->dataNotFound();
        } else {
            $contribution->content = preg_replace('/(\\r\\n)+/m', '<br>', $contribution->content);
            $contribution->type = $contribution->type ? '电信诈骗' : '网络诈骗';
            return $this->responseJson(['contribution' => $contribution]);
        }
    }

    // 软删除
    public function destroy($id)
    {
        $contribution = Contribution::find($id);
        if ($contribution == null) {
            return $this->dataNotFound();
        } else {
            $contribution->delete();
            return $this->responseJson();
        }
    }

    //批量软删除
    public function multipleDestroy(Request $request)
    {
        $idArray = $request->idArray;
        Contribution::whereIn('id', $idArray)->delete();
        return $this->responseJson();
    }

    // 查询回收站内容
    public function trashed(ContributionFormat $contributionForamt)
    {
        $contributions = Contribution::onlyTrashed()->Paginate(90);
        if ($contributions->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $contributionForamt->formatCollection($contributions->toArray());
            return $this->responseJson(['contributions' => $data]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $contribution = Contribution::withTrashed()->find($id);
        if ($contribution == null) {
            return $this->dataNotFound();
        } else {
            $contribution->restore();
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $contribution = Contribution::withTrashed()->find($id);
        if ($contribution == null) {
            return $this->dataNotFound();
        } else {
            $contribution->forceDelete();
            return $this->responseJson();
        }
    }
}
