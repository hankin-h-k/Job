<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('wechat/mobile', 'Auth\LoginController@getPhone');

// Route::middleware('auth')->group(function () {
	//用户列表
	Route::get('users', 'Admin\UsersController@users');
	//用户详情
	Route::get('users/{user}', 'Admin\UsersController@user');
	//兼职列表
	Route::get('jobs', 'Admin\JobsController@jobs');
	//兼职详情
	Route::get('jobs/{job}', 'Admin\JobsController@job')->where('job', '[0-9]+');
	//兼职删除
	Route::delete('jobs/{job}', 'Admin\JobsController@deleteJob');
	//兼职修改
	Route::put('jobs/{job}', 'JobsController@updateJob');
	//添加兼职
	Route::post('jobs', 'JobsController@storeJob');
	//发布兼职
	Route::put('jobs/{job}/status', 'JobsController@updateJobStatus');
	//兼职分类列表
	Route::get('job/categories', 'JobsController@jobCategories');
	//兼职分类详情
	Route::get('job/categories/{categoty}', 'Admin\JobsController@jobCategory')->where('categoty', '[0-9]+');
	//删除兼职分类
	Route::delete('job/categories/{category}', 'JobsController@deleteJobCategory');
	//兼职分类添加
	// Route::get('job/categories', 'Admin\JobsController@storeJobCategory');
	//兼职分类修改
	Route::put('job/categories/{category}', 'JobsController@updateJobCategory');
	//广告列表
	Route::get('ads', 'Admin\AdsController@ads');
	//广告详情
	Route::get('ads/{ad}', 'Admin\AdsController@ad');
	//广告添加
	Route::post('ads', 'AdsController@storeAd');
	//广告删除
	Route::delete('ads/{ad}', 'AdsController@deleteAd');
	//广告修改
	Route::put('ads/{ad}', 'AdsController@updateAd');


// });
/**
 * 文章
 */
//文章列表
Route::get('articles', 'Admin\ArticlesController@articles');
//文章详情
Route::get('articles/{article}', 'Admin\ArticlesController@article');
//文章创建
Route::get('add/articles', 'Admin\ArticlesController@storeArticle');
//文章修改
Route::get('update/articles/{article}', 'Admin\ArticlesController@updateArticle');
//文章删除
Route::get('del/articles/{article}', 'Admin\ArticlesController@deleteArticle');

Route::get('test', 'Controller@test');

Route::get('jobs/{job}/members', 'Admin\JobsController@jobMembers');

Route::get('admins', 'Admin\UsersController@adminUsers');
Route::get('users/{user}/admin', 'Admin\UsersController@updateAdmin');

Route::get('new/user/num', 'Admin\UsersController@newUserNum');
Route::get('addresses', 'HomeController@addresses');