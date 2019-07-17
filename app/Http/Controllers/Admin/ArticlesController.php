<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
class ArticlesController extends Controller
{
    public function articles(Request $request, Article $article)
    {
    	$articles = $article->orderBy('id', 'desc');
        $keyword = $request->input('keyword');
        if ($keyword) {
            $keyword = trim($keyword);
            $articles = $articles->where(function($sql) use($keyword){
                $sql->where('title', 'like', '%'.$keyword.'%')
                ->orWhere('sub_title', 'like', '%'.$keyword.'%');
            });
        }
        $articles = $articles->paginate();
    	return $this->success('ok', $articles);
    }

    public function article(Request $request, Article $article)
    {
    	return $this->success('ok', $article);
    }

    public function storeArticle(Request $request, Article $article)
    {
    	$data['title'] = $request->input('title');
        if (empty($data['title'])) {
            return $this->failure('请输入文章标题');
        }
        $data['sub_title'] = $request->input('sub_title');
        if (empty($data['sub_title'])) {
            return $this->failure('请输入文章子标题');
        }
        $data['content'] = $request->input('content');
        if (empty($data['content'])) {
            return $this->failure('请输入文章内容');
        }
        $data['type'] = $request->input('type');
        if (empty($data['type'])) {
            return $this->failure('请选择文章类型');
        }
        $data['path'] = $request->input('path');
    	$data['pic'] = $request->input('pic');
        
    	$article = $article->create($data);
    	return $this->success('ok', $article);
    }

    public function updateArticle(Request $request, Article $article)
    {
    	if ($request->has('title') && $request->title != $article->title) {
    		$article->title = $request->title;
    	}
        if ($request->has('sub_title') && $request->sub_title != $article->sub_title) {
            $article->sub_title = $request->sub_title;
        }
    	if ($request->has('pic') && $request->pic != $article->pic) {
    		$article->pic = $request->pic;
    	}
    	if ($request->has('content') && $request->content != $article->content) {
    		$article->content = $request->content;
    	}
        if ($request->has('type') && $request->type != $article->type) {
            $article->type = $request->type;
        }
        if ($request->has('path') && $request->path != $article->path) {
            $article->path = $request->path;
        }
    	$article->save();
    	return $this->success('ok', $article);
    }

    public function deleteArticle(Request $request, Article $article)
    {
    	$article->delete();
    	return $this->success('ok');
    }
}
