<?php
namespace app\admin\controller;
//use think\Controller;
use app\admin\model\User;
use app\admin\model\Role;
use app\admin\validate;

class UserController extends CommonController{
    // @用到seesion的需要继承CommonController可防止翻墙 User and Index
    //用户添加
    public function add(){
        //1.判断请求
        if(request()->isPost()){
            $userModel = new User();
            //2.接收参数
            $postData = input('post.');
            //3.验证器验证
            $result = $this->validate($postData,'User.add',[],true);
            if($result !==true){
                $this->error( implode(',', $result) );
            }
            //4.编辑或添加入库
            //给密码password进行加密
//            $postData['password'] = md5($postData['password'].config('password_salt') );
            if($userModel->allowField(true)->save($postData)){
                $this->success('入库成功',url('/admin/user/index'));
            }else{
                $this->error('入库失败');
            }

        }
        //取出所有的角色数据，分配到模板中
        $roles = Role::select();
        return $this->fetch('',['roles'=>$roles]);
    }
    //用户编辑
    public function upd(){
        // 1.判断post提交
        if(request()->isPost()){
            $userModel = new User();
            // 2.接收数据
            $postData = input('post.');
            // 3.验证
            // 密码和确认密码都为空
            if($postData['password']==''&&$postData['repassword']==''){
                $result = $this->validate($postData,'User.onlyUsername',[],true);
                if($result!==true){
                    $this->error( implode(',',$result) );
                }
            }else{
                $result = $this->validate($postData,'User.usernamePassword',[],true);
                if($result!==true){
                    $this->error( implode(',',$result) );
                }
            }
            // 编辑是否成功
            if($userModel->allowField(true)->isUpdate(true)->save($postData) ){
                $this->success('编辑成功',url('/admin/user/index') );
            }else{
                $this->error('编辑失败');
            }
        }
        //get方法
        $user_id = input('user_id');
        $userInfo = User::find($user_id);
        return $this->fetch('',[
            'userInfo' => $userInfo,
        ]);
    }
    //用户列表
    public function index(){
        //调用数据
        $users = User::alias('t1')
            ->field('t1.*,t2.role_name')
            ->join("sh_role t2",'t1.role_id = t2.role_id','left' )
            ->paginate(3);
        //2.输出模板
        return $this->fetch('',[
            'users'=>$users
        ]);
    }
    //用户删除
    public function del(){
        $user_id = input('user_id');
        if(User::destroy($user_id)){
            $this->success('删除成功',url('/admin/user/index') );
        }else{
            $this->error('删除失败');
        }
        // return $this->fetch(''); //没有return
    }

}