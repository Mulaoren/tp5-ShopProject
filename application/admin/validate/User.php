<?php
namespace app\admin\validate;
use think\Validate;

class User extends Validate{

    //验证规则
    protected $rule = [
        //表单上
        'username'      =>'require|unique:user',
        'password'      =>'require|min:5',
        'repassword'    =>'require|confirm:password',
        'role_id'       =>'require',
    ];
    //验证不通过提示信息
    protected $message = [
        'username.require'          => '用户名必填',
        'username.unique'           => '用户名重复',
        'password.require'          => '密码必填',
        'password.min'              => '密码必须大于5位',
        'repassword.require'        => '重复密码必填',
        'repassword.confirm'        => '两次密码不一致',
        'role_id'                   => '请设置角色',//??select可没有require
    ];
    //验证场景
    protected $scene = [
        //场景名=>[元素=>规则1|规则2]
        //场景名=>[元素] 验证元素的所有的规则
        'add'               => ['username','password','repassword','role_id'],// 用户添加
        // 用户编辑
        'onlyUsername'      => ['username'],
        'usernamePassword'  => ['username','password','repassword'],
        'login'  => ['username->require','password'],

    ];

}

