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
//工作列表
Route::get('/jobs', 'JobsController@jobs');
//工作详情
Route::get('/jobs/{job}', 'JobsController@job');
//工作报名
Route::get('jobs/{job}/join', 'JobsController@joinJob');
