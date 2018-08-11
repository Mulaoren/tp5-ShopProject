<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;

class CategoryController extends Controller{

    //点击导航或左侧分类导航[列表]跳转
    public function index(){
        $cat_id = input('cat_id');
        // 获取当前分类id祖先分类(面包屑导航)
        $catModel = new Category();
        $cats = $catModel->select()->toArray();//toArray()使数据更直观展示
        // 获取祖先分类
        $familysCat = $catModel->getFamilysCat($cats,$cat_id);
        //halt($familysCat);

        //两个技巧 1. 以cat_id作为二维数组的下标
        $catsData = [];
        foreach($cats as $v){
            $catsData[$v['cat_id']] = $v;
        }
        //2.以pid为下标,通过pid进行分组
        $children = [];
        foreach($cats as $v){
            $children[ $v['pid'] ][] = $v['cat_id'];
        }
        //获取当前分类的子孙分类cat_id --@排序-销量块展示
        $sonsCatid = $catModel->getSonsCatId($cats,$cat_id);
        //halt($sonsCatid);
        //把当前分类也要加上
        $sonsCatid[] = $cat_id;
        //查询在子孙分类下面的所有商品即可
        // 当前热卖的, 不在回收站的 id在范围内的
        $where = [
            'is_sale' =>1,
            'is_delete' =>0,
            'cat_id' =>['in',$sonsCatid],
        ];
        $goodsData = Goods::where($where)->select()->toArray();
        //halt();

        //分配模板
        return $this->fetch('',[
            'familysCat'=>$familysCat,
            'catsData'  =>$catsData,
            'children'  =>$children,
            'goodsData'  =>$goodsData,
        ]);
    }


}