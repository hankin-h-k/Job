<?php

use Illuminate\Http\Request;



Route::middleware('auth:api')->group(function () {
	/**
	 * 用户
	 */
	//用户列表
	Route::get('users', 'UsersController@users');
	//用户详情
	Route::get('users/{user}', 'UsersController@user');
	//通知用户
	Route::post('inform/user/{user}', 'UsersController@informUser');
	//屏蔽用户
	Route::put('shield/users/{user}', 'UsersController@shieldUser');
	//用户报名列表
	Route::get('users/{user}/applycations', 'UsersController@userApplycations');
	//新增用户
	Route::get('new/user/num', 'UsersController@newUserNum');

	/**
	 * 管理员
	 */
	//管理员列表
	Route::get('admins', 'UsersController@adminUsers');
	//修改管理员
	Route::put('users/{user}/admin', 'UsersController@updateAdmin');
	//添加管理员
	Route::post('admins', 'UsersController@storeAdmin');
	//删除管理员
	Route::delete('users/{user}/admin', 'UsersController@deleteAdmin');

	/**
	 * 兼职
	 */
	//兼职列表
	Route::get('jobs', 'JobsController@jobs');
	//兼职详情
	Route::get('jobs/{job}', 'JobsController@job');
	//兼职删除
	Route::delete('jobs/{job}', 'JobsController@deleteJob');
	//兼职修改
	Route::put('jobs/{job}', 'JobsController@updateJob');
	//添加兼职
	Route::post('jobs', 'JobsController@storeJob');
	//发布兼职
	Route::put('jobs/{job}/status', 'JobsController@updateJobStatus');
	//推荐兼职
	Route::put('recommend/jobs/{job}', 'JobsController@recommendJob');
	//置顶兼职
	Route::put('top/jobs/{job}', 'JobsController@topJob');
	//兼职报名成员
	Route::get('jobs/{job}/members', 'JobsController@jobMembers');
	//兼职分类列表
	Route::get('job/categories', 'JobsController@jobCategories');
	//兼职分类详情
	Route::get('job/categories/{categoty}', 'JobsController@jobCategory');
	//删除兼职分类
	Route::delete('job/categories/{category}', 'JobsController@deleteJobCategory');
	//兼职分类添加
	Route::post('job/categories', 'JobsController@storeJobCategory');
	//兼职分类修改
	Route::put('job/categories/{category}', 'JobsController@updateJobCategory');

	/**
	 * 广告
	 */
	//广告列表
	Route::get('ads', 'AdsController@ads');
	//广告详情
	Route::get('ads/{ad}', 'AdsController@ad');
	//广告添加
	Route::post('ads', 'AdsController@storeAd');
	//广告删除
	Route::delete('ads/{ad}', 'AdsController@deleteAd');
	//广告修改
	Route::put('ads/{ad}', 'AdsController@updateAd');

	/**
	 * 文章
	 */
	//文章列表
	Route::get('articles', 'ArticlesController@articles');
	//文章详情
	Route::get('articles/{article}', 'ArticlesController@article');
	//文章创建
	Route::post('articles', 'ArticlesController@storeArticle');
	//文章修改
	Route::put('articles/{article}', 'ArticlesController@updateArticle');
	//文章删除
	Route::delete('articles/{article}', 'ArticlesController@deleteArticle');
});
