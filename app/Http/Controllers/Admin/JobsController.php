<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategoty;
class JobsController extends Controller
{
    public function jobs(Request $request, Job $job)
    {   
    	$jobs = $job->orderBY('id', 'desc')->paginate();
    	return $this->success('ok', $jobs);
    }

    public function job(Request $request, Job $job)
    {
    	return $this->success('ok', $job);
    }

    public function storeJob(Request $request, Job $job)
    {
    	$data['title'] = $request->input('title');
    	$data['job_time'] = $request->input('job_time');
    	$data['province'] = $request->input('province');
    	$data['city'] = $request->input('city');
    	$data['dist'] = $request->input('dist');
    	$data['address'] = $request->input('address');
    	$data['lng'] = $request->input('lng');
    	$data['lat'] = $request->input('lat');
    	$data['pay_type'] = $request->input('pay_type');
    	$data['reward'] = $request->input('reward');
    	$data['need_num'] = $request->input('need_num');
    	$data['intro'] = $request->input('intro');
    	$data['linkman'] = $request->input('linkman');
    	$data['link_mobile'] = $request->input('link_mobile');
    	$job = $job->create($data);
    	return $this->success('ok', $job);
    }

    public function updateJob(Request $request, Job $job)
    {
    	if ($request->has('title') && $request->title != $job->title) {
    		$job->title = $request->title;
    	}
    	if ($request->has('job_time') && $request->job_time != $job->job_time) {
    		$job->job_time = $request->job_time;
    	}
    	if ($request->has('province') && $request->province != $job->province) {
    		$job->province = $request->province;
    	}
    	if ($request->has('city') && $request->city != $job->city) {
    		$job->city = $request->city;
    	}
    	if ($request->has('dist') && $request->dist != $job->dist) {
    		$job->dist = $request->dist;
    	}
    	if ($request->has('address') && $request->address != $job->address) {
    		$job->address = $request->address;
    	}
    	if ($request->has('lng') && $request->lng != $job->lng) {
    		$job->lng = $request->lng;
    	}
    	if ($request->has('lat') && $request->lat != $job->lat) {
    		$job->lat = $request->lat;
    	}
    	if ($request->has('pay_type') && $request->pay_type != $job->pay_type) {
    		$job->pay_type = $request->pay_type;
    	}
    	if ($request->has('reward') && $request->reward != $job->reward) {
    		$job->reward = $request->reward;
    	}
    	if ($request->has('need_num') && $request->need_num != $job->need_num) {
    		$job->need_num = $request->need_num;
    	}
    	if ($request->has('intro') && $request->intro != $job->intro) {
    		$job->intro = $request->intro;
    	}
    	if ($request->has('linkman') && $request->linkman != $job->linkman) {
    		$job->linkman = $request->linkman;
    	}
    	if ($request->has('link_mobile') && $request->link_mobile != $job->link_mobile) {
    		$job->link_mobile = $request->link_mobile;
    	}
    	$job->save();
    	return $this->success('ok');
    }

    public function deleteJob(Request $request, Job $job)
    {
    	$job->delete();
    	return $this->success('ok');
    }

    public function updateJobStatus(Request $request, Job $job)
    {
    	$status = $request->input('status');
    	$job->update(['status'=>$status]);
    	return $this->success('ok');
    }

    public function jobCategories(Request $request, JobCategoty $category)
    {
    	$categories = $category->orderBy('id', 'desc')->paginate();
    	return $this->success('ok', $categories);
    }

    public function jobCategory(Request $request, JobCategoty $category)
    {
    	return $this->success('ok', $category);
    }

    public function storeJobCategory(Request $request, JobCategoty $category)
    {
    	$data['parent_id'] = $request->input('parent_id', 0);
    	$data['name'] = $request->input('name');
        if (empty($data['name'])) {
            return $this->failure('请输入分类名称');
        }
    	$category = $category->create($data);
    	return $this->success('ok', $category);
    }

    public function updateJobCategory(Request $request, JobCategoty $category)
    {
    	if ($request->has('parent_id') && $request->parent_id != $category->parent_id) {
    		$category->parent_id = $request->parent_id;
    	}
    	if ($request->has('name') && $request->name != $category->name) {
    		$category->name = $request->name;
    	}
    	$category->save();
    	return $this->success('ok');
    }
}
