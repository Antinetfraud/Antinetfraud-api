<?php

//管理员路由
Route::group(['namespace' => 'Admin'], function ($router) {
    $router->get('init', 'LoginController@showInit');
    $router->post('init', 'LoginController@init');
    $router->get('login', 'LoginController@login');
    $router->post('login', 'LoginController@login');
    $router->get('info', 'AdminController@info');
});

Route::group(['middleware' => 'auth.admin', 'namespace' => 'Admin'], function ($router) {
    $router->get('state', 'AdminController@state');
    $router->put('password/reset', 'AdminController@resetPassword');
    $router->get('admin/all', 'AdminController@all');
    $router->post('admin/create', 'AdminController@create');
    $router->delete('admin/{id}', 'AdminController@destroy');
    $router->get('admin/trashed', 'AdminController@trashed');
    $router->put('admin/restore/{id}', 'AdminController@restore');
    $router->post('admin/force/delete/{id}', 'AdminController@delete');

    $router->post('apk/upload/', 'AppController@apkUpload');
    $router->post('app/create/', 'AppController@store');

    $router->post('article/image/upload/', 'ArticleController@imgUpload');
    $router->post('article/create', 'ArticleController@store');
    $router->put('article/update/{id}', 'ArticleController@update');
    $router->post('article/delete/multiple', 'ArticleController@multipleDestroy');
    $router->delete('article/{id}', 'ArticleController@destroy');
    $router->get('article/all', 'ArticleController@all');
    $router->get('article/trashed', 'ArticleController@trashed');
    $router->get('article/tag/{id}', 'ArticleController@showByTag');
    $router->get('article/show/{id}', 'ArticleController@show');
    $router->get('article/edit/{id}', 'ArticleController@edit');
    $router->post('article/force/delete/multiple', 'ArticleController@multipleDelete');
    $router->post('article/force/delete/{id}', 'ArticleController@delete');
    $router->put('article/restore/{id}', 'ArticleController@restore');

    $router->get('comment/all', 'CommentController@all');
    $router->post('comment/delete/multiple', 'CommentController@multipleDestroy');
    $router->delete('comment/{id}', 'CommentController@destroy');
    $router->put('comment/reply/{id}', 'CommentController@reply');
    $router->put('comment/pass/{id}', 'CommentController@pass');
    $router->put('comment/block/{id}', 'CommentController@block');
    $router->get('comment/trashed', 'CommentController@trashed');
    $router->put('comment/restore/{id}', 'CommentController@restore');
    $router->post('comment/force/delete/{id}', 'CommentController@delete');

    $router->get('notice/all', 'NoticeController@all');
    $router->get('notice/show/{id}', 'NoticeController@show');
    $router->post('notice', 'NoticeController@store');
    $router->put('notice/update/{id}', 'NoticeController@update');
    $router->post('notice/delete/multiple', 'NoticeController@multipleDestroy');
    $router->delete('notice/{id}', 'NoticeController@destroy');
    $router->get('notice/trashed', 'NoticeController@trashed');
    $router->put('notice/restore/{id}', 'NoticeController@restore');
    $router->post('notice/force/delete/{id}', 'NoticeController@delete');

    $router->post('logout', 'LoginController@logout');

    $router->get('user/all', 'UserController@all');
    $router->put('user/block/{id}', 'UserController@block');
    $router->put('user/unblock/{id}', 'UserController@unblock');
    $router->post('user/delete/multiple', 'UserController@multipleDestroy');
    $router->delete('user/{id}', 'UserController@destroy');
    $router->get('user/trashed', 'UserController@trashed');
    $router->put('user/restore/{id}', 'UserController@restore');
    $router->post('user/force/delete/{id}', 'UserController@delete');

    $router->get('contribution/all', 'ContributionController@all');
    $router->get('contribution/show/{id}', 'ContributionController@show');
    $router->post('contribution/delete/multiple', 'ContributionController@multipleDestroy');
    $router->delete('contribution/{id}', 'ContributionController@destroy');
    $router->get('contribution/trashed', 'ContributionController@trashed');
    $router->put('contribution/restore/{id}', 'ContributionController@restore');
    $router->post('contribution/force/delete/{id}', 'ContributionController@delete');

});

