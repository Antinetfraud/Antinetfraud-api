<?php

namespace App\Http\Controllers\Admin;

use App\Model\App;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AppController extends ApiController
{
    public function apkUpload(Request $request)
    {
        $file = $_FILES['apk'];
        //得到文件名称
        $name = $file['name'];

        //上传文件的存放路径
        $upload_path = public_path('app/');

        //得到文件类型，并且都转化成小写
        $type = strtolower(substr($name, strrpos($name, '.') + 1));
        //定义允许上传的类型
        $allow_type = array('apk');
        //把非法格式的文件去除
        if (!in_array($type, $allow_type)) {
            unset($name);
            return $this->somethingWrong('上传失败，文件类型不合法');
        }

        //得到文件类型，并且都转化成小写
        $type = strtolower(substr($name, strrpos($name, '.') + 1));
        $file_name = 'antinetfraud-' . date("YmdHis") . "." . $type;
        $upload_name = $upload_path . $file_name;
        //dd($file_name);
        if (move_uploaded_file($file['tmp_name'], $upload_name)) {
            return $this->responseJson(['path' => asset('app/' . $file_name)]);
        } else {
            return $this->somethingWrong('上传失败');
        }

    }

    public function store(Request $request)
    {
        $input = $request->all();
        App::create($input);
        return $this->responseJson();
    }
}
