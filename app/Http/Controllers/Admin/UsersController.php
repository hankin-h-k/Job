<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
class UsersController extends Controller
{
    public function users(Request $request, User $user)
    {
    	$users = $users->orderBy('id', 'desc')->paginate();
    	return $this->success('ok', $users);
    }

    public function user(Request $request, User $user)
    {
    	return $this->success('ok', $user);
    }
}
