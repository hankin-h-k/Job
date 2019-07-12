<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Article;
use App\Models\Job;
use App\Models\Address;
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
    public function home(Request $request, Ad $ad, Article $article, Job $job)
    {
        //广告列表
        $ads = $ad->all();
        //文章
        $articles = $article->all();
        //推荐兼职
        $type = $request->input('type', 'MONTHLY');
        $jobs = $job->where('is_recommend', 1)->where('pay_type', $type)->paginate();
        return $this->success('ok', compact('ads', 'articles', 'jobs'));
    }

    /**
     * 地址
     * @param  Request $request [description]
     * @param  Address $address [description]
     * @return [type]           [description]
     */
    public function addresses(Request $request, Address $address)
    {
        $addresses = $address->where('parent_id', 0)->get();
        foreach ($addresses as $address_obj) {
            $sub_addresses = $address->where('parent_id', $address_obj->id)->get();
            $address_obj->sub_addresses = $sub_addresses;
        }
        return $this->success('ok', $addresses);
    }
}
