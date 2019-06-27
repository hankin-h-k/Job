<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api', 'add_form_id'])->group(function () {
	/**
	 * 工作
	 */
	//工作列表
	Route::get('/jobs', 'JobsController@jobs');
	//工作详情
	Route::get('/jobs/{job}', 'JobsController@job');
	//工作报名
	Route::post('jobs/{job}/join', 'JobsController@joinJob');
	//收餐工作
	Route::post('collect/jobs/{job}', 'JobsController@collectJob');

	/**
	 * 我的简历
	 */
	//我的简历
	Route::get('user', 'UserController@user');
	//修改简历
	Route::put('user', 'UserController@updateUser');

});

