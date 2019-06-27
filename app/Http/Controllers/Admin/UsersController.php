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
    	$users = $user->orderBy('id', 'desc')->paginate();
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
    	$param = [];
    	\WechatService::informUser($param);
    	return $this->success('ok');
    }

    public function admins(Request $request)
    {
        
    }
    
}
