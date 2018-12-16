<?php

namespace App\Http\Controllers\Admin;

use App\Format\Admin\ArticleFormat;
use App\Format\Admin\ArticleByTagFormat;
use App\Model\Article;
use App\Model\BrowserHistory;
use App\Model\Collection;
use App\Model\Tag;
use Carbon\Carbon;
use Parsedown;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ArticleController extends ApiController
{
    // 获取文章列表
    public function all(ArticleFormat $articleFormat)
    {
        $articles = Article::latest('created_at')->with('tag')->Paginate(9);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $articleFormat->formatCollection($articles->toArray());
            return $this->responseJson(['articles' => $data]);
        }
    }

    // 按标签分类
    public function showByTag($id, ArticleByTagFormat $articleFormat)
    {
        $tag = Tag::find($id);
        $articles = Article::where('tag_id', $id)->latest('created_at')->Paginate(9);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            foreach ($articles as $article) {
                $article->tag_name = $tag->name;
            }
            $data = $articleFormat->formatCollection($articles->toArray());
            return $this->responseJson(['articles' => $data, 'title' => $tag->name]);
        }
    }

    // 显示某个文章
    public function show($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $parseDown = new Parsedown();
            $article->content = $parseDown->text($article->content);
            $article->tag = Tag::find($article->tag_id)->name;
            return $this->responseJson(['article' => $article]);
        }
    }

    // 修改某个文章
    public function edit($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $article->tag = Tag::find($article->tag_id)->name;
            return $this->responseJson(['article' => $article]);
        }
    }

    // 创建文章
    public function store(Request $request)
    {
        $input = $request->all();
        Article::create($input);
        return $this->responseJson();
    }

    // 更新文章
    public function update($id, Request $request)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $input = $request->all();
            $article->update($input);
            return $this->responseJson();
        }
    }

    // 彻底删除
    public function delete($id)
    {
        $article = Article::withTrashed()->find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $article->forceDelete();
            return $this->responseJson();
        }
    }

    // 批量彻底删除
    public function multipleDelete(Request $request)
    {
        $idArray = $request->idArray;
        $articles = Article::whereIn('id', $idArray)->forceDelete();
        return $this->responseJson();
    }

    // 查询回收站内容
    public function trashed()
    {
        $articles = Article::onlyTrashed()->Paginate(90);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->responseJson(['articles' => $articles]);
        }
    }

    // 从回收站还原
    public function restore($id)
    {
        $article = Article::withTrashed()->find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $article->restore();
            return $this->responseJson();
        }
    }

    // 批量还原
    public function multipleRestore(Request $request)
    {
        $idArray = $request->idArray;
        $articles = Article::withTrashed()->whereIn('id', $idArray)->restore();
        return $this->responseJson();
    }

    // 软删除
    public function destroy($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $article->delete();
            BrowserHistory::where('article_id',$id)->delete();
            Collection::where('article_id',$id)->delete();
            return $this->responseJson();
        }
    }

    //批量软删除
    public function multipleDestroy(Request $request)
    {
        $idArray = $request->idArray;
        Article::whereIn('id', $idArray)->delete();
        return $this->responseJson();
    }

    //图片上传
    public function imgUpload(Request $request)
    {
        $file = $request->file('image');
        //构造一个新的名字
        $filename = Carbon::now()->toDateString()
            . md5(rand(1, 1000) . $file->getClientOriginalName())
            . "." . $file->getClientOriginalExtension();
        $file->move(public_path('images/'), $filename);
        $image = '/images/' . $filename;
        return $this->responseJson(['image' => $image]);
    }
}
