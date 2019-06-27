<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad;
class AdsController extends Controller
{
    public function ads(Request $request, Ad $ad)
    {
    	$ads = $ad->orderBy('id', 'desc')->paginate();
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

    public function storeAd(Request $request, Ad $ad)
    {
    	$data['pic'] = $request->input('pic');
    	$data['path'] = $request->input('path');
    	$ad = $ad->create($data);
    	return $this->success('ok', $data);
    }

    public function deleteAd(Request $request, Ad $ad)
    {
    	$ad->delete();
    	return $this->success('ok');
    }
}
