<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//第一个参数url，第二个参数是admin模块下的NewsController
use think\Route;
Route::resource('index/index','index/Index');
Route::resource('index/indexx','index/Indexx');
Route::resource('admin/material','admin/Material');
Route::resource('admin/upload','admin/upload');
Route::resource('admin/help','admin/Help');
Route::resource('oauth/oauth','oauth/Oauth');
Route::resource('oauth/userinfo','oauth/Userinfo');
Route::resource('oauth/help','oauth/Help');
Route::resource('oauth/query','oauth/Query');
//Route::resource('user/user','user/user');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
