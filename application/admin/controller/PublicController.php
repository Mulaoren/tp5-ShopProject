<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\Model\User;

class PublicController extends Controller{
    // 后台登录
    public function login(){
        // echo '111';
        // 1.判断是否为post请求
        if(request()->isPost() ){
            // 2.接收数据
            $postData = input('post.');
            // 3.验证器验证
            $result = $this->validate($postData,'User.login',[],true);
            if($result !==true){
                $this->error( implode(',', $result) );
            }
            // 验证码验证 参考前台
            // 4.判断是否登录成功（把验证逻辑写在模型中）
            $userModel = new User();
            if($userModel->checkUser($postData['username'],$postData['password']) ){
                //$this->redirect('/');
                $this->redirect('/houtai');
            }else{
                $this->error('用户名或密码失败');
            }
        }
        return $this->fetch('');
    }

    // 后台退出
    public function logout(){
        // 清空保存的用户信息
        session('user_id', null);
        session('username', null);
        // 跳转登录页面
        $this->redirect("/admin/public/login");
    }



}