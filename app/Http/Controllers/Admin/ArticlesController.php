<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
class ArticlesController extends Controller
{
    public function articles(Request $request, Article $article)
    {
    	$articles = $articles->OrderBy('id', 'desc')->paginate();
    	return $this->success('ok', $articles);
    }

    public function article(Request $request, Article $article)
    {
    	return $this->success('ok', $article);
    }

    public function storeArticle(Request $request, Article $article)
    {
    	$data['title'] = $request->input('title');
    	$data['pic'] = $request->input('pic');
    	$data['path'] = $request->input('path');
    	$article = $article->create($data);
    	return $this->success('ok', $article);
    }

    public function updateArticle(Request $request, Article $article)
    {
    	if ($request->has('title') && $request->title != $article->title) {
    		$article->title = $request->title;
    	}
    	if ($request->has('pic') && $request->pic != $article->pic) {
    		$article->pic = $request->pic;
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
