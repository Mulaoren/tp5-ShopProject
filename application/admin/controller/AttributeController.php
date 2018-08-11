<?php
namespace app\admin\controller;
//use think\Controller;
use app\admin\model\Type;
use app\admin\model\Attribute;

class AttributeController extends CommonController{
    //商品属性编辑
    public function upd(){
        // 判断是否是post请求
        if(request()->isPost()){
            // 接收post参数
            $postData = input('post.');
            // 验证器验证
            if($postData['attr_input_type']==1){
                $result = $this->validate($postData,'Attribute.add',[],true);
            }else{
                $result = $this->validate($postData,'Attribute.except_attr_values',[],true);
            }
            if($result !== true){
                $this->error(implode(',',$result));
            }
            // 实例化模型写入数据库
            $attributeModel = new Attribute();
            if($attributeModel->update($postData)){
                $this->success("编辑成功",url("/admin/attribute/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        // 分配数据
        $attr_id = input('attr_id');
        $attribute = Attribute::find($attr_id);
        // 取出商品类型
        $types = Type::select();
        return $this->fetch('',[
            'attribute'      =>$attribute,
            'types'  =>$types,
        ]);
    }
    // 商品属性列表展示
    public function index(){
        // 取出所有的属性的属性, 分配到模板中
        $attributes = Attribute::alias('t1')
            ->field('t1.*,t2.type_name')
            ->join('sh_type t2','t1.type_id = t2.type_id','left')
            ->select();
        return $this->fetch('',['attributes'=>$attributes]);
    }

    public function add(){
        // 商品属性添加
        // 判断是否是post请求
        if(request()->isPost()){
            // 接收post参数
            $postData = input('post.');
            //halt($postData);
            // 验证器验证
            if($postData['attr_input_type']==1){
                $result = $this->validate($postData,'Attribute.add',[],true);
            }else{
                $result = $this->validate($postData,'Attribute.except_attr_values',[],true);
            }
            if($result !== true){
                $this->error(implode(',',$result));
            }
            // 实例化模型写入数据库
            $attributeModel = new Attribute();
            if($attributeModel->allowField(true)->save($postData)){
                $this->success("添加成功",url("/admin/attribute/index"));
            }else{
                $this->error("添加失败");
            }
        }
        // 取出商品类型
        $types = Type::select();
        return $this->fetch('',['types'=>$types]);
    }

}