<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->group(function () {
	//用户列表
	Route::get('users', 'UsersController@users');
	//用户详情
	Route::get('users/{user}', 'UsersController@user');
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
	Route::put('release/jobs/{job}', 'JobsController@releaseJob');
	//取消兼职
	Route::put('cancel/jobs/{job}', 'JobsController@cancelJob');
	//兼职分类列表
	Route::get('job/categories', 'JobsController@jobCategories');
	//兼职分类详情
	Route::get('job/categories/{categoty}', 'JobsController@jobCategory');
	//删除兼职分类
	Route::delete('job/categories/{category}', 'JobsController@deleteJobCategory');
	//兼职分类添加
	Route::post('job/categories', 'JobsController@storeJobCategories');
	//兼职分类修改
	Route::put('job/categories/{category}', 'JobsController@updateJobCategories');
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
});
