<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad;
class AdsController extends Controller
{
    public function ads(Request $request, Ad $ad)
    {
    	$ads = $ad->orderBy('id', 'desc')->get();
    	return $this->success('ok', $ads);
    }

    public function ad(Request $request, Ad $ad)
    {
    	return $this->success('ok', $ad);
    }

    public function updateAd(Request $request, Ad $ad)
    {
    	if ($request->has('pic') && $request->pic != $ad->pic) {
    		$ad->pic = $request->pic;
    	}
    	if ($request->has('path') && $request->path != $ad->path) {
    		$ad->path = $request->path;
    	}
    	$ad->save();
    	return $this->success('ok', $ad);
    }

    public function storeAd(Request $request, Ad $ad_obj)
    {
    	//删除已有的
        $ad_obj->where('id', '>', 0)->delete();
        //创建最新的
        $ads = $request->input('ads');
        if ($ads && count($ads)) {
            foreach ($ads as $ad) {
                $ad = $ad_obj->create([
                    'path'=>$ad['path'],
                    'pic'=>isset($ad['pic'])?$ad['pic']:null,
                    'content'=>$ad['content'],
                ]);
            }
        }
        return $this->success('ok');
    }

    public function deleteAd(Request $request, Ad $ad)
    {
    	$ad->delete();
    	return $this->success('ok');
    }
}
