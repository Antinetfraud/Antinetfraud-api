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

//api路由
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test','Admin\QuestionController@store');
Route::get('password/reset','Auth\ResetPasswordController@test')->name('password.reset');


Route::group(['namespace' => 'Api'],function ($router){
    $router->post('contribution', 'ContributionController@store');
    $router->get('notice/all','NoticeController@all');
    $router->get('notice/show/{id}','NoticeController@show');
    $router->get('comment/show/{id}','CommentController@show');
    $router->get('app/latest','AppController@latest');
    $router->get('feedback','FeedbackController@store');
    $router->get('question','QuestionController@random');
});

Route::group(['prefix'=>'article','namespace' => 'Api'],function ($router){
    $router->get('all','ArticleController@all');
    $router->get('hot','ArticleController@hot');
    $router->get('tag/{id}','ArticleController@showByTag');
    $router->get('show/{id}','ArticleController@show');
    $router->get('search/{keywords}','ArticleController@search');
    $router->post('read/{id}','ArticleController@read');
    $router->post('praise/{id}','ArticleController@praise');
});

//用户路由
Route::group(['prefix'=>'auth','namespace' => 'Auth'],function ($router){
    $router->post('login','LoginController@login');
    $router->post('register','RegisterController@register');
    $router->get('check/email','RegisterController@checkEmail');
    $router->get('check/name','RegisterController@checkName');
    $router->post('password/reset/email','ForgotPasswordController@sendResetLinkEmail');
    $router->post('password/reset','ResetPasswordController@reset');
    // 发送激活邮箱的邮件
    $router->post('email/activate','AuthController@sendActivateEmail');
    // 激活某个邮箱
    $router->get('activate/email/','AuthController@activateEmail');
    // 发送邮箱验证码
    $router->post('email/verify','AuthController@sendVerifyEmail');
});

Route::group(['middleware'=>'auth.api','prefix'=>'auth','namespace' => 'Auth'],function ($router){
    $router->get('state','AuthController@state');
    $router->get('info','AuthController@info');
    $router->post('logout','AuthController@logout');

    $router->post('article/comment','CommentController@store');

    $router->post('article/collection','CollectionController@store');
    $router->delete('article/collection/cancel','CollectionController@destory');
    $router->get('collection','CollectionController@show');
    $router->get('collection/article/{id}','CollectionController@check');

    $router->post('article/read','BrowserHistoryController@store');
    $router->get('history','BrowserHistoryController@show');
});
