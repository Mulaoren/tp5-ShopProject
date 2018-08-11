<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Goods extends Model{
    protected $pk = 'goods_id';
    protected $autoWriteTimestamp = true;

    protected static function init(){
        Goods::event('before_insert',function($goods){
            //设置货号唯一
            $goods['goods_sn'] = date('ymdhis').uniqid();
        });


        //入库后的事件after_insert ,完商品属性的入库到商品属性表（sh_goods_attr）
        Goods::event('after_insert',function($goods){
            //$goods 当表单对象数据入库成功之后，返回表的记录数据对象，其中带着自增主键goods_id
            $goods_id = $goods['goods_id'];
            $postData = input('post.');
            $goodsAttrValue = $postData['goodsAttrValue'];
            $goodsAttrPrice = $postData['goodsAttrPrice'];
            //因为有多个属性，所以需要循环向商品属性表（sh_goods_attr）进行入库
            foreach($goodsAttrValue as $attr_id=>$attr_value){
                //单选属性$attr_value是一个数组
                if(is_array($attr_value)){
                    foreach($attr_value as $k => $singel_attr_value){
                        $data = [
                            'goods_id' => $goods_id,
                            'attr_id'  => $attr_id,
                            'attr_value'=>$singel_attr_value,
                            //通过下标获取单选属性对应的价格
                            'attr_price'=>$goodsAttrPrice[$attr_id][$k],
                            'create_time' => time(),
                            'update_time' => time()
                        ];
                        //入库到商品属性表
                        Db::name('goods_attr')->insert($data);
                    }
                }else{
                    //唯一属性$attr_value是一个字符串
                    $data = [
                        'goods_id' => $goods_id,
                        'attr_id'  => $attr_id,
                        'attr_value'=>$attr_value,
                        'create_time' => time(),
                        'update_time' => time()
                    ];
                    //入库到商品属性表
                    Db::name('goods_attr')->insert($data);
                }
            }
        });
    }

    // 多文件上传方法
    public function uploadImg(){
        // dump(request()->file('img'));// 反回控制器测试数据
        $files = request()->file('img'); // [file,file]
        $goods_img=[]; // 用于存储文件上传的路径
        // 接收上传文件的name的名称
        $files = request()->file('img');
        if($files){
            //文件上传的要求
            $validate = [
              'size' =>3*1024*1024, //3M
              'ext'  =>'jpg,png,gif,jpeg',// 允许的后缀
            ];
            // 上传的目录
            $uploadDir = "./static/upload"; //上传用./ , 访问用/
            foreach($files as $file){
                $info = $file->validate($validate)->move($uploadDir);
                if($info){
                    //存储文件的路径到数据中去,把反斜杠替换成正斜杠
                    $goods_img[] = str_replace('\\','/',$info->getSaveName());
                }
            }
        }
        return $goods_img;
    }

    // 进行缩略图的生成
    public function thumb($goods_img){
        //Image打开源图片 $image = \Think\Image::open(源图片);
        //进行缩放处理 $image -> thumb(宽度，高度，类型)
        //把缩放好的图片保存到服务器上 $image -> save(小图路径名);
        $goods_middle = [];
        $goods_thumb = [];
        //$goods_img 数据为$goods_img [20180714/gsdfg.jpg,20180714/middle_gsdgfgfg.jpg]
        foreach($goods_img as $path){
            $arr_path = explode('/',$path);
            $middle_path = $arr_path[0].'/middle_'.$arr_path[1];
            //打开原图路径
            $image = \think\Image::open('./static/upload/'.$path);
            // 按照原图的比例生成一个最大为350*350的缩略图并保存为 2参数补白
            $image->thumb(350, 350,2)->save('./static/upload/'.$middle_path);
            // 存储中图
            $goods_middle[] = $middle_path;
        }
        foreach($goods_img as $path){
            $arr_path = explode('/',$path);
            $thumb_path = $arr_path[0].'/thumb_'.$arr_path[1];
            //打开原图路径
            $image = \think\Image::open('./static/upload/'.$path);
            // 按照原图的比例生成一个最大为350*350的缩略图并保存为 2参数补白
            $image->thumb(50, 50,2)->save('./static/upload/'.$thumb_path);
            // 存储中图
            $goods_thumb[] = $thumb_path;
        }
        return ['goods_middle'=>$goods_middle,'goods_thumb'=>$goods_thumb];
    }

}

















