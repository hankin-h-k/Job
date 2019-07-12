<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApplicationForm;
class UsersController extends Controller
{
    public function users(Request $request, User $user)
    {	
        $is_completed = $request->input('is_completed');
    	$users = $user->where('is_completed', $is_completed);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $keyword = trim($keyword);
            $users = $users->where(function($sql) use($keyword){
                $sql->where('mobile', 'like', '%'.$keyword.'%')
                ->orWhere('name', 'like', '%'.$keyword.'%');
            });
        }
        $users = $users->orderBy('id', 'desc')->paginate();
    	return $this->success('ok', $users);
    }

    public function user(Request $request, User $user)
    {
    	return $this->success('ok', $user);
    }

    public function userApplycations(Request $request, User $user, ApplicationForm $form)
    {
    	$forms = $user->forms()->with('job')->paginate();
    	return $this->success('ok', $forms);
    }

    public function informUser(Request $request, User $user)
    {
        $form_id_obj = $user->getValidFormId();
        if (empty($form_id_obj)) {
            return $this->failure('用户没有通知标签');
        }
    	$param = [];
    	\WechatService::informUser($param);
    	return $this->success('ok');
    }

    /**
     * 屏蔽用户
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function shieldUser(Request $request, User $user)
    {
        $user->is_shielded = $user->is_shielded?0:1;
        $user->save();
        return $this->success('ok', $user);
    }   

    /**
     * 管理员用户
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function adminUsers(Request $request, User $user)
    {
        $users = $user->where('is_admin', 1);
        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $users = $users->where(function($sql) use($keyword){
                $sql->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('mobile', 'like', '%'.$keyword.'%');
            });
        }
        $users = $users->paginate();
        return $this->success('ok', $users);
    }

    public function storeAdmin(Request $request, User $user)
    {
        $name = $request->input('name');
        if (empty($name)) {
            return $this->failure('请输入名称');
        }
        $mobile = $request->input('mobile');
        if (empty($mobile)) {
            return $this->failure('请输入手机号');
        }
        $password = $request->input('password');
        if (empty($password)) {
            return $this->failure('请输入密码');
        }
        $user_obj = $user->where('mobile', $mobile)->first();
        if (empty($user_obj)) {
            $user_obj = new User();
            $user_obj->mobile = $mobile;
        }
        $user_obj->password = bcrypt($password);
        $user_obj->name = $name;
        $user_obj->email = $mobile.'@test.com';
        $user_obj->is_admin = 1;
        $user_obj->save();
        return $this->success('ok', $user_obj);

    }

    /**
     * 删除管理员
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function deleteAdmin(Request $request, User $user)
    {
        $user->is_admin =0;
        $user->save();
        return $this->success('ok');
    }

    /**
     * 添加\删除管理员
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function updateAdmin(Request $request, User $user)
    {
        if ($user->is_admin) {//删除管理员
            $user->update(['is_admin'=>0]);
        }else{
            $user->update(['is_admin'=>1, 'password'=>bcrypt($user->mobile)]);
        }
        return $this->success('ok');
    }

    public function newUserNum(Request $request, User $user)
    {
        //所有数据；
        $all_num = $user->count();
        //当天注册人数
        $today_num = $user->where('created_at', '>=', date('Y-m-d'))->where('created_at', '<', date('Y-m-d', strtotime('+1 day')))->count();
        //最近七天增长人数
        $result = $this->getWeekStat();
        $user_num_arr = $result['user_num_arr'];
        $day_arr = $result['day_arr'];
        return $this->success('ok', compact('today_num', 'user_num_arr', 'day_arr', 'all_num'));
    }

    /**
     * 最近七天增长人数
     * @param  [type] $user_ids    [description]
     * @param  [type] $from_openid [description]
     * @return [type]              [description]
     */
    public function getWeekStat()
    {   
        $end_time = date('Y-m-d', time());
        $start_time = date('Y-m-d', strtotime('-6 day'));
        //时间段
        $day_arr = $this->daliy($start_time, $end_time);
        $user_num_arr = [];
        for ($i=0; $i < count($day_arr); $i++) { 
            $day_start_time = $day_arr[$i];
            if ($i < count($day_arr) - 1) {
                $day_end_time = $day_arr[$i + 1];
            }else{
                $day_end_time = date('Y-m-d', strtotime('+1 day', strtotime($day_start_time)));
            }
            $user_num = User::whereBetween('created_at', [$day_start_time, $day_end_time])->count();
            $user_num_arr[] = $user_num;

        }
        return $array = [
            'user_num_arr'=>$user_num_arr,
            'day_arr'=>$day_arr
        ];
    }
}
