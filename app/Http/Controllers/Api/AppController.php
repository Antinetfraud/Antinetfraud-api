<?php

namespace App\Http\Controllers\Api;

use App\Mail\UserMailer;
use App\Model\App;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Model\BrowserHistory;
use function MongoDB\BSON\toJSON;

class AppController extends ApiController
{
    //返回最新的APP的版本号，更新信息，更新时间和下载地址
    public function latest()
    {
        $app=App::latest('id')->first();
        if($app==null){
            return $this->dataNotFound();
        }else{
            return $this->responseJson(['app'=>$app]);
        }
    }

    public function testMail(UserMailer $userMailer, Request $request)
    {
        $browserHistories = BrowserHistory::where('user_id', 2)->with('article')
            ->latest('updated_at')
            ->paginate(6);
        foreach ($browserHistories as $k => $browserHistory) {
            if ($browserHistory->article == null) {
                unset($browserHistories[$k]);
                $browserHistory->delete();
            }
        }
        dd($browserHistories->ToArray());
    }
}
