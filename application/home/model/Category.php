<?php
namespace app\home\model;
use think\Model;

class Category extends Model{
    // 表的主键字段
	protected $pk = 'cat_id';

    // 获取导航栏的分类的数据
    public function getNavData($limit){
        //
        return $this->where('is_show','=','1')->limit($limit)->select();//$this指Category模型
    }

    public function getFamilysCat($data,$cat_id){
        static $result = [];
        foreach($data as $k=>$v){
            //pid(0)=>1->1第一次循环,先找到自己,找到自己寻自己的pid
            if($v['cat_id']==$cat_id){
                $result[] = $v;
                //删除已经判断过的分类项
                unset($data[$k]);
                //递归调用
                $this->getFamilysCat($data,$v['pid']);
            }
        }
        //因为找出的父类是往数组后推进的,所以这里返回的数组数据应反转
        return array_reverse($result);
    }
    //取出子孙分类
    public function getSonsCatId($data, $cat_id){
        static $sonsId = [];
        foreach($data as $k=>$v){
            //出口
            if($v['pid'] == $cat_id){
                $sonsId[] = $v['cat_id']; // 注:只存储cat_id即可
                //
                unset($data[$k]);
                //递归
                $this->getSonsCatId($data,$v['cat_id']);
            }
        }
        return $sonsId;
    }

}
