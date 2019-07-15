<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\ApplicationForm;
class JobsController extends Controller
{   
    /**
     * 兼职列表
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function jobs(Request $request, Job $job)
    {   
        $jobs = $job->orderBy('id', 'desc');
        $status = $request->input('status');
        if ($status && $status != 'ALL') {
            $jobs = $jobs->where('status', $status);
        }
        $keyword = $request->input('keyword');
        if ($keyword) {
            $jobs = $jobs->where('title', 'like', '%'.$keyword.'%');
        }
        $jobs = $jobs->paginate();
    	return $this->success('ok', $jobs);
    }

    /**
     * 兼职详情
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function job(Request $request, Job $job)
    {
        $category = $job->category;
    	return $this->success('ok', $job);
    }

    /**
     * 添加兼职
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function storeJob(Request $request, Job $job)
    {
        $data['pic'] = $request->input('pic');
    	$data['title'] = $request->input('title');
        $data['sub_title'] = $request->input('sub_title');
        $data['reward'] = $request->input('reward');
        $data['need_num'] = $request->input('need_num');
        $data['category_id'] = $request->input('category');
    	$data['job_time'] = $request->input('job_time');
        $data['pay_type'] = $request->input('pay_type');
        $data['linkman'] = $request->input('linkman');
        $data['link_mobile'] = $request->input('link_mobile');
        $data['wechat'] = $request->input('wechat');
        $data['link_email'] = $request->input('link_email');
    	$data['province'] = $request->input('province');
    	$data['city'] = $request->input('city');
    	$data['dist'] = $request->input('dist');
    	$data['address'] = $request->input('address');
    	$data['lng'] = $request->input('lng');
    	$data['lat'] = $request->input('lat');
    	$data['intro'] = $request->input('intro');
        $data['explain'] = $request->input('explain');
    	$job = $job->create($data);
    	return $this->success('ok', $job);
    }

    /**
     * 修改兼职
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function updateJob(Request $request, Job $job)
    {
        if ($request->has('pic') && $request->pic != $job->pic) {
            $job->pic = $request->pic;
        }
    	if ($request->has('title') && $request->title != $job->title) {
    		$job->title = $request->title;
    	}
        if ($request->has('sub_title') && $request->sub_title != $job->sub_title) {
            $job->sub_title = $request->sub_title;
        }
        if ($request->has('reward') && $request->reward != $job->reward) {
            $job->reward = $request->reward;
        }
        if ($request->has('need_num') && $request->need_num != $job->need_num) {
            $job->need_num = $request->need_num;
        }
        if ($request->has('category_id') && $request->category_id != $job->category_id) {
            $job->category_id = $request->category_id;
        }
    	if ($request->has('job_time') && $request->job_time != $job->job_time) {
    		$job->job_time = $request->job_time;
    	}
        if ($request->has('pay_type') && $request->pay_type != $job->pay_type) {
            $job->pay_type = $request->pay_type;
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
    	if ($request->has('intro') && $request->intro != $job->intro) {
    		$job->intro = $request->intro;
    	}
        if ($request->has('explain') && $request->explain != $job->explain) {
            $job->explain = $request->explain;
        }
    	if ($request->has('linkman') && $request->linkman != $job->linkman) {
    		$job->linkman = $request->linkman;
    	}
    	if ($request->has('link_mobile') && $request->link_mobile != $job->link_mobile) {
    		$job->link_mobile = $request->link_mobile;
    	}
        if ($request->has('wechat') && $request->wechat != $job->wechat) {
            $job->wechat = $request->wechat;
        }
        if ($request->has('link_email') && $request->link_email != $job->link_email) {
            $job->link_email = $request->link_email;
        }
    	$job->save();
    	return $this->success('ok');
    }

    /**
     * 删除兼职
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function deleteJob(Request $request, Job $job)
    {
    	$job->delete();
    	return $this->success('ok');
    }

    /**
     * 修改兼职状态
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function updateJobStatus(Request $request, Job $job)
    {
    	$status = $request->input('status');
    	$job->update(['status'=>$status]);
    	return $this->success('ok');
    }

    /**
     * 兼职报名成员
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function jobMembers(Request $request, Job $job, ApplicationForm $form)
    {
        $forms = $form->with('user', 'job')->where('job_id', $job->id);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $keyword = trim($keyword);
            $forms = $forms->whereHas('user', function($sql) use($keyword){
                $sql->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('mobile', 'like', '%'.$keyword.'%');
            });
        }
        $forms = $forms->orderBy('id', 'desc')->paginate();
        return $this->success('ok', $forms);
    }

    /**
     * 兼职分类列表
     * @param  Request     $request  [description]
     * @param  JobCategoty $category [description]
     * @return [type]                [description]
     */
    public function jobCategories(Request $request, JobCategory $category_obj)
    {
    	$categories = $category_obj->where('parent_id',0);
        $nopage = $request->input('nopage', 0);
        if ($nopage) {
            $categories = $categories->get();
        }else{
            $categories = $categories->paginate();
        }

        foreach ($categories as $category) {
             $sub_categories = $category_obj->where('parent_id',$category->id)->get();
             $category->sub_categories = $sub_categories;
        }
    	return $this->success('ok', $categories);
    }

    /**
     * 兼职分类详情
     * @param  Request     $request  [description]
     * @param  JobCategoty $category [description]
     * @return [type]                [description]
     */
    public function jobCategory(Request $request, JobCategory $category)
    {
    	return $this->success('ok', $category);
    }

    /**
     * 添加兼职分类
     * @param  Request     $request  [description]
     * @param  JobCategoty $category [description]
     * @return [type]                [description]
     */
    public function storeJobCategory(Request $request, JobCategory $category)
    {
    	$data['parent_id'] = $request->input('parent_id', 0);
    	$data['name'] = $request->input('name');
        if (empty($data['name'])) {
            return $this->failure('请输入分类名称');
        }
    	$category = $category->create($data);
    	return $this->success('ok', $category);
    }

    /**
     * 修改兼职分类
     * @param  Request     $request  [description]
     * @param  JobCategoty $category [description]
     * @return [type]                [description]
     */
    public function updateJobCategory(Request $request, JobCategory $category)
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

    /**
     * 删除兼职分类
     * @param  Request     $request  [description]
     * @param  JobCategory $category [description]
     * @return [type]                [description]
     */
    public function deleteJobCategory(Request $request, JobCategory $category)
    {
        if ($category->parent_id == 0) {
            $category->where('parent_id', $category->id)->delete();
        }
        $category->delete();
        return $this->success('ok');
    }

    /**
     * 取消推荐、推荐兼职
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function recommendJob(Request $request, Job $job)
    {
        $job->is_recommend = $job->is_recommend?0:1;
        $job->save();
        return $this->success('ok', $job);
    }

    /**
     * 只听兼职
     * @param  Request $request [description]
     * @param  Job     $job     [description]
     * @return [type]           [description]
     */
    public function topJob(Request $request, Job $job)
    {
        $job->top();
        return $this->success('ok');
    }
}
