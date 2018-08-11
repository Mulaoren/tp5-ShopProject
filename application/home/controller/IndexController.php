<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;

class IndexController extends Controller{
    //购物车测试
    public function test(){
        //实例化购物车类
        $cart = new \cart\Cart();
        //halt($cart);
    }
    //
    public function index(){
        //取出导航分类
        $categoryModel = new Category();
        $navDatas = $categoryModel->getNavData(5);

        //取出"首页"左侧的三级分类筛选的数据
        $oldCat = Category::select();
        //两个技巧
        $cats = [];
        foreach($oldCat as $cat){
            $cats[ $cat['cat_id'] ] = $cat;
        }
        $children = [];
        foreach($oldCat as $cat){
            $children[ $cat['pid'] ][] = $cat['cat_id'];
        }

        //取出前台推荐位的商品
        $goodsModel = new Goods();
        $crazyDatas = $goodsModel->getGoods('is_crazy',5);
        $hotDatas = $goodsModel->getGoods('is_hot',5);
        $bestDatas = $goodsModel->getGoods('is_best',5);
        $newDatas = $goodsModel->getGoods('is_new',5);

        return $this->fetch('',[
            'navDatas'=>$navDatas,
            'cats'=>$cats,
            'children'=>$children,
            'crazyDatas'=>$crazyDatas,
            'hotDatas'=>$hotDatas,
            'bestDatas'=>$bestDatas,
            'newDatas'=>$newDatas
        ]);
    }

}

