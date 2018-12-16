<?php

namespace App\Http\Controllers\Auth;

use App\Format\CollectionFormat;
use App\Model\Collection;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CollectionController extends ApiController
{
    // 添加收藏记录
    public function store(Request $request)
    {
        $input = $request->all();
        /*
        * 因为最初的设计缺陷，用id表示user_id。
        * 然后在这个API里面，需要把id改成user_id并把id unset掉。
        * 因为collection表的id是自增的。
        * */
        $input['user_id'] = $input['id'];
        unset($input['id']);

        $collection = Collection::where(
            ['user_id' => $input['user_id'], 'article_id' => $input['article_id']])->first();
        if ($collection == null) {
            Collection::create($input);
            return $this->responseJson();
        } else {
            return $this->somethingWrong("已收藏，请勿重复收藏");
        }
    }

    //根据用户id显示他的收藏
    public function show(Request $request, CollectionFormat $collectionFormat)
    {
        $input = $request->all();
        $collections = Collection::latest('created_at')->where('user_id', $input['id'])->with('article')->paginate(6);
        if ($collections->isEmpty()) {
            return $this->dataNotFound();
        } else {
            //把tag的id整合为一个数组
            $tagIds = array();
            foreach ($collections as $collection) {
                array_push($tagIds, $collection->article->tag_id);
            }
            //用whereIn一次性把tag拿出来
            $tags = Tag::whereIn('id', $tagIds)->get();
            //把结果做成一个 id => name 键值对数组
            $tagArray = array();
            foreach ($tags as $tag) {
                $tagArray[$tag->id] = $tag->name;
            }
            //根据tag_id赋值tag_name
            foreach ($collections as $collection) {
                $collection->article->tag_name = $tagArray[$collection->article->tag_id];
            }
            //过滤不需要的字段，返回response
            $collections = $collectionFormat->formatCollection($collections->toArray());
            return $this->responseJson(['collections' => $collections]);
        }
    }

    // 取消收藏，删除记录
    public function destory(Request $request)
    {
        $sum = Collection::where(
            ['user_id' => $request->id, 'article_id' => $request->article_id])->delete();
        if ($sum == 1) {
            return $this->responseJson();
        } else {
            return $this->dataNotFound();
        }
    }

    // 检查该用户有没有收藏该文章
    public function check($id, Request $request)
    {
        $collection = Collection::where(['user_id' => $request->id, 'article_id' => $id])->first();
        if ($collection == null) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson();
        }
    }
}
