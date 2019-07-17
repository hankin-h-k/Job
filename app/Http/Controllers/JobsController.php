<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\ApplicationForm;
use App\Models\JobCategory;
use App\Models\Address;
use App\Models\User;
class JobsController extends Controller
{
    /**
    * 工作列表
    */
    public function jobs(Request $request, Job $job)
    {
        $type = $request->input('type', 'DAILY');
        $jobs = $job->where('pay_type', $type)->where('status', 'UNDERWAY');
        $keyword = $request->input('keyword');
        if ($keyword) {
          $keyword = trim('keyword');
          $jobs = $jobs->where(function($sql) use($keyword){
            $sql->where('title', 'like', '%'.$keyword.'%');
          });
        }
        $category_id = $request->input('category_id');
        if ($category_id) {
            $jobs = $jobs->where('category_id', $category_id);
        }
        $dist = $request->input('dist');
        if ($dist) {
            $jobs = $jobs->where('dist', $dist);
        }
        $order = $request->input('order');
        if ($order == 'new') {
            $jobs = $jobs->orderBy('id', 'desc');
        }else{
            $jobs = $jobs->orderBy('is_top', 'desc')->orderBy('is_recommend', 'desc')->orderBy('id', 'desc');
        }      

        $jobs = $jobs->paginate();
        return $this->success('ok', $jobs);
    }

      /**
      * 工作详情
      */
    public function job(Request $request, Job $job, JobCategory $category)
    {
        $category_name = '';
        $sub_category_name = '';
        //工作类型
        if ($job->category_id) {
            $job_category = $category->where('id', $job->category_id)->first();
            $sub_jon_category = $category->where('id', $job_category->parent_id)->first();
            $category_name = $job_category->name;
            $sub_category_name = $sub_jon_category->name;
        }
        $job->category_name = $category_name;
        $job->sub_category_name = $sub_category_name;
        $category = $job->category;
        //已报名人
        $members = $job->forms()->with('user')->limit(7)->orderBy('id', 'desc')->get();
        $user = auth()->user();
        //是否收藏
        $job->is_collected = $user->jobCollects()->where('job_id', $job->id)->first()?1:0;
        //是否报名
        $job->is_joined = $user->isJoined($job);
        return $this->success('ok', compact('job', 'members'));
    }

    /**
    * 参加工作
    */
    public function joinJob(Request $request, Job $job)
    {
        //是否已完成招聘
        if ($job->status == 'FINISHED') {
            return $this->failure('该工作已结束招聘');
        }
        //报名人数是否完成
        if ($job->joined_num == $job->need_num) {
            return $this->failure('该工作招聘人数已满'); 
        }
        //是否已参加招聘
        $user = auth()->user();
        $result = $user->isJoined($job);
        if ($result) {
            return $this->failure('你已报名该工作');
        }
        //报名
        $user->forms()->create([
            'job_id'=>$job->id,
        ]);
        //报名人数+1
        $job->increment('joined_num', 1);
        return $this->success('ok');
    }

    /**
     * 取消报名
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function cancelJoinJob(Request $request, Job $job)
    {   
        $user = auth()->user();
        $result = $user->isJoined($job);
        if (empty($result)) {
            return $this->failure('还未报名该工作');
        }
        $user->forms()->where('job_id', $job->id)->delete();
        $job->decrement('joined_num', 1);
        return $this->success('ok');
    }

    /**
     * 收藏工作
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function collectJob(Request $request, Job $job)
    {
        $user = auth()->user();
        //收藏 、取消收藏
        $user->collectJob($job);
        return $this->success('ok');
    }

    /**
     * 活动分类
     * @param  Request     $request  [description]
     * @param  JobCategory $category [description]
     * @return [type]                [description]
     */
    public function jobCategories(Request $request, JobCategory $category_obj)
    {
        $categories = $category_obj->where('parent_id', 0)->get();
        foreach ($categories as $category) {
            $sub_categories = $category_obj->where('parent_id', $category->id)->get();
            $category->sub_categories = $sub_categories;
        }
        return $this->success('ok', $categories);
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
