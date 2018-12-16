<?php

namespace App\Http\Controllers\Api;

use App\Format\ArticleFormat;
use App\Format\ArticleByTagFormat;
use App\Model\Article;
use App\Model\Tag;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Parsedown;

class ArticleController extends ApiController
{
    protected $articleFormat;

    public function __construct(ArticleFormat $articleFormat)
    {
        $this->articleFormat = $articleFormat;
    }

    public function all()
    {
        $articles = Article::latest('created_at')->with('tag')->Paginate(6);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $this->articleFormat->formatCollection($articles->toArray());
            return $this->responseJson(['articles' => $data, 'title' => "最新发布"]);
        }
    }

    public function show($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        } else {
            $parseDown = new Parsedown();
            $article->content = $parseDown->text($article->content);
            $tag = Tag::find($article->tag_id)->name;
            return $this->responseJson(['article' => $article, 'tag' => $tag]);
        }
    }

    public function showByTag($id, ArticleByTagFormat $articleFormat)
    {
        $tag = Tag::find($id);
        $articles = Article::where('tag_id', $id)
            ->latest('created_at')->Paginate(6);
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

    public function hot()
    {
        $articles = Article::latest('reading')->with('tag')->Paginate(6);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $this->articleFormat->formatCollection($articles->toArray());
            return $this->responseJson(['articles' => $data, 'title' => "最热文章"]);
        }
    }

    public function search($keywords)
    {
        $articles = Article::where('title', 'like', '%' . $keywords . '%')
            ->orWhere('content', 'like', '%' . $keywords . '%')
            ->with('tag')->Paginate(6);
        if ($articles->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $data = $this->articleFormat->formatCollection($articles->toArray());
            return $this->responseJson(['articles' => $data, 'title' => "搜索结果"]);
        }
    }

    public function read($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        }
        $article->reading++;
        $article->update();
        return $this->responseJson();
    }

    public function praise($id)
    {
        $article = Article::find($id);
        if ($article == null) {
            return $this->dataNotFound();
        }
        $article->praise++;
        $article->update();
        return $this->responseJson();
    }

//    public function test()
//    {
//        echo Carbon::now();
//        $articles=Article::all();
//        foreach ($articles as $article){
//            $article->created_at=Carbon::createFromTimeStamp($article->createtime);
//            $article->update();
//        }
//        echo Carbon::now();
//        echo "success";
//    }
}
