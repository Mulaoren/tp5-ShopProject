<?php
namespace app\admin\controller;
//use think\Controller;
use app\admin\model\Auth;

class AuthController extends CommonController{
    //权限编辑
    public function upd(){
        // 1.判断表示方法post
        if(request()->isPost()){
            // 2.接收数据
            $postData = input('post.');
            // 3.验证器验证
            if($postData['pid']==0){
                $result = $this->validate($postData,'Auth.onlyAuthName',[],true);
            }else{
                $result = $this->validate($postData,'Auth.upd',[],true);
            }
            if($result!==true){
                $this->error( implode(',',$result));
            }
            // 4.判断是否写入
            $authModel = new Auth();
            if($authModel->update($postData) ){
                $this->success("编辑成功",url('/admin/auth/index') );
            }else{
                $this->error("编辑失败");
            }
        }
        // 获取当前权限的数据,分配到模板中,输出模板
        $auth_id = input('auth_id');
        $auth = Auth::find($auth_id);
        // 取出所有的无限极分类的权限
        $authModel = new Auth();
        $auths = $authModel->getSonsAuth($authModel->select() );
        return $this->fetch('',[
            'auth'=>$auth,
            'auths'=>$auths,
        ]);


    }
    //权限列表展示
    public function index(){
        // 实例化auth模型，取出数据，分配到模板，输出模板
        $auth = Auth::field('t1.*,t2.auth_name p_name')
            ->alias('t1')
            ->join("sh_auth t2",'t1.pid = t2.auth_id','left')
            ->select();
        $authModel = new Auth();
        $auths = $authModel->getSonsAuth($auth);
        return $this->fetch('',['auths'=>$auths]);
    }
    //
    public function add(){
        //echo '111';
        //1.判断语法方法
        if(request()->isPost()){
            // 2.接收数据
            $postData = input('post.');
            // 3.验证器验证 @顶级权限没有控制器和方法
            // 判断是否是顶级
            if($postData['pid']==0){
                $result = $this->validate($postData,'Auth.onlyAuthName',[],true);
            }else{
                $result = $this->validate($postData,'Auth.add',[],true);
            }

            if($result !==true){
                $this->error( implode(',',$result) );
            }
            // 4.判断入库成功否
            $authModel = new Auth();
            if($authModel->save($postData)){
                $this->success('',url('/admin/auth/index'));
            }else{
                $this->error('添加失败');
            }
        }
        // get获取数据
        $authModel = new Auth;
        $auths = $authModel->getSonsAuth( $authModel->select() );
        return $this->fetch('',['auths'=>$auths]);
    }

}