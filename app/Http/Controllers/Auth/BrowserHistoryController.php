<?php

namespace App\Http\Controllers\Auth;

use App\Format\BrowserHistoryFormat;
use App\Model\BrowserHistory;
use App\Model\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BrowserHistoryController extends ApiController
{
    public function store(Request $request)
    {
        $input = $request->all();
        /*
         * 因为最初的设计缺陷，用id表示user_id。
         * 然后在这个API里面，需要把id改成user_id并把id unset掉。
         * 因为browser history表的id是自增的。
         * */
        $input['user_id'] = $input['id'];
        unset($input['id']);

        $browserHistory = BrowserHistory::where(
            ['user_id' => $input['user_id'],
                'article_id' => $input['article_id']
            ])->first();
        if ($browserHistory == null) {
            BrowserHistory::create($input);
        } else {
            $browserHistory->updated_at = Carbon::now();
            $browserHistory->update();
        }

        return $this->responseJson();
    }

    //根据用户id显示他的浏览记录
    public function show(Request $request, BrowserHistoryFormat $browserHistoryFormat)
    {
        $input = $request->all();
        $browserHistories = BrowserHistory::where('user_id', $input['id'])->with('article')
            ->latest('updated_at')
            ->paginate(6);
        if ($browserHistories->isEmpty()) {
            return $this->dataNotFound();
        } else {
            //把tag的id整合为一个数组
            $tagIds = array();
            foreach ($browserHistories as $k => $browserHistory) {
                if ($browserHistory->article != null) {
                    array_push($tagIds, $browserHistory->article->tag_id);
                } else {
                    unset($browserHistories[$k]);
                    $browserHistory->delete();
                }
            }
            //用whereIn一次性把tag拿出来
            $tags = Tag::whereIn('id', $tagIds)->get();
            //把结果做成一个 id => name 键值对数组
            $tagArray = array();
            foreach ($tags as $tag) {
                $tagArray[$tag->id] = $tag->name;
            }
            //根据tag_id赋值tag_name
            foreach ($browserHistories as $k => $browserHistory) {
                if ($browserHistory->article != null) {
                    $browserHistory->article->tag_name = $tagArray[$browserHistory->article->tag_id];
                } else {
                    unset($browserHistories[$k]);
                    $browserHistory->delete();
                }

            }
            //过滤不需要的字段，返回response
            $browserHistories = $browserHistoryFormat->formatCollection($browserHistories->toArray());
            return $this->responseJson(['histories' => $browserHistories]);
        }
    }
}
