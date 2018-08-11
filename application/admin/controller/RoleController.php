<?php
namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;

class RoleController extends CommonController{
    //角色编辑
    public function upd(){
        if(request()->isPost()){
            //接收参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,"Role.upd",[],true);
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //写入数据库
            $roleModel = new Role();
            if($roleModel->update($postData)){
                $this->success("编辑成功",url("/admin/role/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        $role_id = input('role_id');
        //取出所有的权限
        $oldAuths = Auth::select()->toArray();
        //以每个权限的auth_id作为$oldAuths二维数组的下标
        $auths = [];
        foreach($oldAuths as $v){
            $auths[ $v['auth_id'] ] = $v;
        }
        //根据pid进行分组，把具有相应的pid分为同一组
        $children = [];
        foreach( $oldAuths as $vv){
            $children[ $vv['pid'] ][] = $vv['auth_id'] ;
        }
        //取出当前角色已有的权限
        $role = Role::find( $role_id);
        return $this->fetch('',[
            'auths' => $auths,
            'children' => $children,
            'role' => $role,
        ]);
    }
    //角色列表
    public function index(){
        //
        $roles = Db::query("select t1.*, GROUP_CONCAT(t2.auth_name) as all_auth from sh_role  t1 left join sh_auth t2  on FIND_IN_SET(t2.auth_id,t1.auth_ids_list) group by t1.role_id");
        return $this->fetch('',['roles'=>$roles]);
    }
    // 角色添加
    public function add(){
        // 1.判断请求是否是post
        if(request()->isPost()){
            // 2.接收参数
            $postData = input('post.');
            // 3.验证器验证
            $result = $this->validate($postData,"Role.add",[],true);
            if( $result !== true ){
                $this->error( implode(',',$result) );
            }
            // 4.写入数据库
            $roleModel = new Role();
            if($roleModel->save( $postData)){
                $this->success("添加成功",url("/admin/role/index"));
            }else{
                $this->error("添加失败");
            }
        }
        $authModel = new Auth();
        $oldauths = $authModel->select()->toArray();

        // 更换下标
        $auth = [];
        foreach($oldauths as $v){
            $auths[$v['auth_id']] = $v;
        }
        // 将所胡的权限通过pid进行分组, 把pid相同的划分到同一组
        $chirldren=[];
        foreach($oldauths as $vv){
            $chirldren[$vv['pid']][] = $vv['auth_id'];
        }
        // 分配到模板
        return $this->fetch('',[
            'auths' => $auths,
            'children' => $chirldren,
        ]);
    }
}