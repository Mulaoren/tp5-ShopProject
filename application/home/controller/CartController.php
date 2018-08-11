<?php
namespace app\home\controller;
use think\Controller;
use think\Db;

class CartController extends Controller{
    //清空购物车商品
    public function clearCartGood(){
        if(request()->isAjax()){
            //调用购物车的方法clearCart清空购物车
            $cart = new \cart\Cart();
            if($cart->clearCart()){
                $response = ['code'=>200,'message'=>'删除成功'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>200,'message'=>'删除失败'];
                echo json_encode($response);die;
            }
        }
    }
    //删除购物车商品
    public function delCartGood(){
        if(request()->isAjax()){
            //1.接收参数
            $goods_id = input('goods_id');
            $goods_attr_ids = input('goods_attr_ids');
            //2.通过购物车的delCart方法删除商品
            $cart = new \cart\Cart();
            //3.判断是否删除成功，返回json数据
            if( $cart->delCart($goods_id,$goods_attr_ids) ){
                $response = ['code'=>200,'message'=>'删除成功'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>-1,'message'=>'删除失败'];
                echo json_encode($response);die;
            }
        }
    }
    // 购物车模板展示处理方法
    public function cartlist(){
        $member_id = session('member_id');
        if(!$member_id){
            $this->error("请先登录，在操作");
        }
        //1.通过购物车类的getCart方法获取购物车的数据
        $cart = new \cart\Cart();
        $carts = $cart->getCart();
        //2.构造一定的数组结构
        $cartData = [];
        foreach($carts as $key => $goods_number){
            $arr = explode('-',$key);
            $goods_id = $arr[0];
            $goods_attr_ids = $arr[1];
            $cartData[]=[
                'goods_id' =>$goods_id,
                'goods_attr_ids' =>$goods_attr_ids,
                'goods_number' =>$goods_number,
                'goodsInfo' => Db::name('goods')->find($arr[0]),
                'attr'=>Db::name("goods_attr")
                    ->field("sum(t1.attr_price)  attrTotalPrice,GROUP_CONCAT(t2.attr_name,':',t1.attr_value SEPARATOR '<br/>') as attrInfo")
                    ->alias('t1')
                    ->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
                    ->where("t1.goods_id = ".$goods_id.' and t2.attr_type = 1 and  t1.goods_attr_id in '."(".$goods_attr_ids.")")
                    ->find()
            ];
        }
        return $this->fetch('',['cartData'=>$cartData]);

    }
    //添加商品到购物车
    public function addgoodstocart(){
        if( request()->isAjax() ){
            //1.判断有无登录
            $member_id = session("member_id");
            if(!$member_id){
                $response = ['code'=>-1, 'message'=>'请先登录后操作'];
                echo json_encode($response);die;
            }
            //2.接收参数
            $goods_id = input('goods_id');
            $goods_number = input('goods_number');
            $goods_attr_ids = input('goods_attr_ids');
            //3.调用购物车方法进行商品的入库
            $cart = new \cart\Cart();

            $result = $cart->addCart($goods_id,$goods_attr_ids,$goods_number);
            if($result){
                $response = ['code'=>200,'message'=>'加入购物车成功'];
                echo json_encode($response);die;
            }else{
                //上面code = -1
                $response = ['code'=>-2,'message'=>'加入购物车失败,稍后重试'];
                echo json_encode($response);die;
            }

        }
    }


}