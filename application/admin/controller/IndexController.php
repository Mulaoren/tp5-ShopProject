<?php
namespace app\admin\controller;

use think\Controller;

class IndexController extends Controller{

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