<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------



use think\Route;

//Route::get('admin','admin/Index/index');
//Route::resource('goods','admin/Goods');
//Route::get('order','admin/Order/index');
//Route::get('order/detail','admin/Order/detail');
//Route::get('user','admin/User/index');
//Route::get('login','admin/Login/index');
//Route::resource('pic','admin/Pic');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST,GET');
if(request()->isOptions()){
    exit();
}

 return [
     '__pattern__' => [
         'name' => '\w+',
     ],
     '[hello]'     => [
         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
         ':name' => ['index/hello', ['method' => 'post']],
     ],

 ];
