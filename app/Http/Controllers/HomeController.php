<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Article;
use App\Models\Job;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home(Ad $ad, Article $Article, Job $job)
    {
        //广告列表
        $ads = $ad->all();
        //文章
        $articles = $article->all();
        //推荐兼职
        $jobs = $job->where('is_recommend', 1)->paginate();
        return $this->success('ok', compact('ads', 'articles', 'jobs'));
    }

}
