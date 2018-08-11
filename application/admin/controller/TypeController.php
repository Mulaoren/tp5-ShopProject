<?php
namespace app\admin\controller;
//use think\Controller;
use app\admin\model\Type;
use app\admin\model\Attribute;

class TypeController extends CommonController{
    // 获取商品类型下面的属性
    public function getAttr(){
        $type_id = input('type_id');
        // 只获取type_name的字段值
        $type_name = Type::where("type_id",'=',$type_id )->value('type_name');
        $attributes = Attribute::where('type_id','=',$type_id)->select();
        return $this->fetch('',[
            'type_name' => $type_name,
            'attributes' => $attributes,
        ]);
    }
    // 商品类型删除
    public function del(){
        $type_id = input('type_id');
        if(Type::destroy($type_id)){
            $this->success('删除成功',url('/admin/type/index') );
        }else{
            $this->error('删除失败');
        }
    }
    //商品类型编辑
    public function upd(){
        // 1.判断是否是post请求
        if(request()->isPost()){
            // 2.接收post参数
            $postData = input('post.');
            // 3.验证器验证
            $result = $this->validate($postData,'Type.upd',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            // 4.实例化模型写入数据库
            $typeModel = new Type();
            if($typeModel->update($postData)){
                $this->success("编辑成功",url("/admin/type/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        // 数据分配到模板
        $type_id = input('type_id');
        $type = Type::find($type_id);
        return $this->fetch('',['type'=>$type]);
    }
    //商品类型列表展示
    public function index(){
        $types = Type::select();

        return $this->fetch('',['types'=>$types]);

    }
    // 商品类型添加
    public function add(){
        // 1.判断是否是post请求
        if(request()->isPost()){
            // 2.接收post参数
            $postData = input('post.');
            // 3.验证器验证
            $result = $this->validate($postData,'Type.add',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            // 4.实例化模型写入数据库
            $typeModel  = new Type();
            if($typeModel->allowField(true)->save($postData)){
                $this->success("添加成功",url("/admin/type/index"));
            }else{
                $this->error("添加失败");
            }
        }
        // 数据分配到模板
        return $this->fetch('');
    }

}