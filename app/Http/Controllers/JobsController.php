<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\ApplicationForm;
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
   		$jobs = $jobs->paginate();
   		return $this->success('ok', $jobs);
   	}

   	/**
   	 * 工作详情
   	 */
   	public function job(Request $request, Job $job)
   	{
   		return $this->success('ok', $job);
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
  		$job->increment('joined_num', 1);
  		return $this->success('ok');
   	}
}
