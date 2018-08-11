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

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

// 开启路由
use think\Route;

// 前台首页
Route::get('/', 'home/index/index');

// 前台home路由分组
Route::group('home', function(){
    //清空购物车商品
    Route::any('cart/clearCartGood','home/cart/clearCartGood');
    //购物车结算
    //ajax删除购物车商品
    Route::any('cart/delCartGood','home/cart/delCartGood');
    //购物车列表页
    Route::any('cart/cartlist','home/cart/cartlist');
    //将商品添加到购物车
    Route::any('cart/addgoodstocart','home/cart/addgoodstocart');
    //购物车测试
    //Route::any('index/test','home/index/test');

    //商品详情页面
    Route::any('goods/detail','home/goods/detail');
    // 分类列表页的面包屑导航
    Route::any('category/index','home/category/index');

    // QQ回调函数
    Route::any('member/qqcallback','home/member/qqCallback');
    // QQ登录
    Route::any('member/qqlogin','home/member/qqlogin');

    // 重置新密码的路由
    Route::any('public/setnewpassword/:member_id/:hash/:time','home/public/setnewpassword');
    // 发送邮件
    Route::get('public/sendemail','home/public/sendemail');
    // 忘记密码
    Route::get('public/forgetPassword','home/public/forgetPassword');
    // ajax验证短信发送
    Route::get('public/sendsms','home/public/sendSms');

    // 前台注册
    Route::any('public/register', 'home/public/register');
    // 前台登录
    Route::any('public/login','home/public/login');
    // 退出登录
    Route::any('public/logout','home/public/logout');

});

// 后台首页
//Route::get('/', 'admin/index/index');
Route::get('/houtai', 'admin/index/index');// 上条不行就用这条

// 后台admin路由分组
Route::group('admin', function(){

    /***************************后台商品管理路由***********************************/
    // Ajax获取指定类型的所有的属性
    Route::get('goods/getTypeAttr','admin/goods/getTypeAttr');
    //权限列表
    Route::get('goods/index','admin/goods/index');
    //编辑权限
    Route::any('goods/upd','admin/goods/upd');
    //添加权限
    Route::any('goods/add','admin/goods/add');
    //删除权限
    Route::get('goods/del','admin/goods/del');

    /***************************后台商品分类管理路由***********************************/
    //权限列表
    Route::get('category/index','admin/category/index');
    //编辑权限
    Route::any('category/upd','admin/category/upd');
    //添加权限
    Route::any('category/add','admin/category/add');
    //删除权限
    Route::get('category/del','admin/category/del');

    /***************************后台属性管理路由***********************************/
    //权限列表
    Route::get('attribute/index','admin/attribute/index');
    //编辑权限
    Route::any('attribute/upd','admin/attribute/upd');
    //添加权限
    Route::any('attribute/add','admin/attribute/add');
    //删除权限
    Route::get('attribute/del','admin/attribute/del');

    /***************************后台商品类型管理路由***********************************/
    // 查看商品类型的属性列表
    Route::get('type/getattr','admin/type/getattr');
    // 权限列表
    Route::get('type/index','admin/type/index');
    //
    Route::any('type/upd','admin/type/upd');
    //
    Route::any('type/add','admin/type/add');
    //
    Route::get('type/del','admin/type/del');

    /***************************后台角色管理路由***********************************/
    // 角色添加
    Route::any('role/add', 'admin/role/add');
    // 角色列表
    Route::get('role/index', 'admin/role/index');
    // 角色编辑
    Route::get('role/upd', 'admin/role/upd');

    /***************************后台权限管理路由***********************************/
    //权限列表
    Route::get('auth/index', 'admin/auth/index');
    // 权限添加
    Route::any('auth/add', 'admin/auth/add');
    // 权限编辑
    Route::any('auth/upd', 'admin/auth/upd');
    // 权限删除
    Route::get('auth/del','admin/auth/del');

    /***************************后台用户管理路由***********************************/
    // 后台首页路由
    Route::get('index/top', 'admin/index/top');
    Route::get('index/left', 'admin/index/left');
    Route::get('index/main', 'admin/index/main');
    // 用户添加
    Route::any('user/add', 'admin/user/add');
    // 用户列表
    Route::get('user/index', 'admin/user/index');
    // 用户删除
    Route::get('user/del', 'admin/user/del');
    // 用户编辑
    Route::any('user/upd', 'admin/user/upd');

    /**********************登录和退出的路由***************************************/
    //后台登录
    Route::any('public/login', 'admin/public/login');
    //退出登录
    Route::get('public/logout', 'admin/public/logout');


});





