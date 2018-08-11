<?php
namespace app\admin\controller;

//use think\Controller;

class IndexController extends CommonController{
    // @用到seesion的需要继承CommonController可防止翻墙 User and Index
    //后台首页
    public function index(){
        //echo '111';
        return $this->fetch('');
    }
    public function top(){
        //
        return $this->fetch('');
    }
    public function left(){
        //
        return $this->fetch('');
    }
    public function main(){
        //
        return $this->fetch('');
    }
}