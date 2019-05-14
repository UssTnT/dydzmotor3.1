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

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/

use think\Route;

Route::get([
  'about'=>'index/about/about',
  'product$'=>'index/product/product',
  'facilities'=>'index/facilities/facilities',
  'solutions'=>'index/solutions/solutions',
  'news$'=>'index/news/news',
  'news/:id$'=>'index/news/view',
  'jobs'=>'index/job/job',
  'contact$'=>'index/contact/contact',
  'contact/:id'=>'index/contact/contact',
  'admin$'=>'admin/admin/login',
  'admin/index$'=>'admin/index/index',
  'admin/create$'=>'admin/admin/create',
  'admin/basic$'=>'admin/admin/basic',
  'admin/info$'=>'admin/admin/info',
  'admin/view/:id$'=>'admin/admin/view',
  'product/:id$'=>'index/product/view',
  'product/category/:id$'=>'index/product/category',
  'news/:id'=>'index/news/view'
]);

Route::post([
  'admin/editBasic$'=>'admin/admin/editBasic',
  'admin/editPwd$'=>'admin/admin/editPwd',
  'product/search'=>'index/product/search',
  'send'=>'index/contact/send'
]);

