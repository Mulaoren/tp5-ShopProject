<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;

class PublicController extends Controller{
    //重设密码
    public function setNewPassword($member_id,$hash,$time){
        //判断邮件地址是否被篡改，判断hash加密字符串的结果，不一样则被篡改了
        if( md5($member_id.$time.config('email_salt')) != $hash   ){
            exit('你对地址做啥了');
        }
        //判断是否在有效期内30分钟
        if(time()>$time+1800){
            exit('早干嘛去了，现在晚了');
        }
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,"Member.setNewPassword",[],true);
            if($result!==true){
                $this->error( implode(',',$result) );
            }
            //更新密码
            $data = [
                'member_id' => $member_id,
                'password'  => md5( $postData['password'].config('password_salt') )
            ];
            $memModel = new Member();
            if($memModel->update($data)){
                $this->success("重置密码成功",url("/home/public/login"));
            }else{
                $this->error("重置失败");
            }
        }

        return $this->fetch('');
    }
    //发送邮件
    public function sendEmail(){
        // dump($_SERVER);die;
        if(request()->isAjax()){
            //接收参数
            $email = input('email');
            //验证器邮箱必须存在系统中
            $result = Member::where('email','=',$email)->find();
            if(!$result){
                //说明没有这个邮箱
                $response = ['code'=>-1,'message'=>'邮箱不存在'];
                echo json_encode($response);die;
            }
            //构造找回密码的链接地址

            $member_id = $result['member_id'];
            $time = time();
            $hash = md5($result['member_id'].$time.config('email_salt')); //把用户和id和邮箱的盐进行加密，防止用户篡改
            $href =$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php/home/public/setNewPassword/".$member_id.'/'.$hash.'/'.$time;
            $content = "<a href='{$href}' target='_blank'>京西商城-找回密码</a>";
            //发送邮件
            if( sendEmail([$email],'找回密码',$content) ){
                $response = ['code'=>200,'message'=>'发送成功，请登录邮箱查看'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>-2,'message'=>'发送失败，请稍后再试'];
                echo json_encode($response);die;
            }
        }
    }
    // 忘记密码点击->邮箱
    public function forgetpassword(){
        return $this->fetch('');
    }
    //发送短信
    public function sendSms(){
        if(request()->isAjax()){
            // 接收phone参数
            $phone = input('phone');
            //注：验证的数据为数组格式
            $result = $this->validate(["phone"=>$phone],"Member.sendSms",[],false);
            if($result!==true){
                //返回ajax说明手机号已被注册过,不能注册
                $response = ['code'=>-1,'message'=>'手机号占用,请更换一个'];
                //ajax希望接收json数据
                echo json_encode($response);die;
            }
            //发送信息 调用application/common.php中定义的sendSms方法
            $rand = mt_rand(1000,9999);
            $result = sendSms($phone,array($rand,'5'),'1');
            //判断是否发送成功,返回json数据
            if($result->statusCode == '000000'){
                //存cookie方便下面判断没被改动过,给手机验证码加盐处理,设置有效期5分钟
                cookie('phone',md5($rand.config('sms_salt')),300);
                $response = ['code'=>200,'message'=>'发送短信成功'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>-2,'message'=>'网络异常请重试或'.$result->statusMsg];
                echo json_encode($response);die;
            }
        }
    }
    // 退出登录
    public function logout(){
        // 清除session
        session('member_id',null);
        session('member_name',null);
        $this->redirect('/home/public/login');
    }

    // 登录
    public function login(){
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,"Member.login",[],true);
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //判断用户名和密码是否匹配
            $memModel = new Member();
            $flag = $memModel->checkUser($postData['username'],$postData['password']);
            if($flag){
                //判断是否有goods_id,如果有，返回到对应的商品详情页
                if(input('goods_id')){
                    $this->redirect("/home/goods/detail",['goods_id'=>input('goods_id')]);
                }
                $this->redirect("/");
            }else{
                $this->error("用户名或密码失败");
            }

        }
        return $this->fetch('');
    }

    // 注册
    public function register(){
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,"Member.register",[],true);
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //判断手机验证码是否正确
            if(md5($postData['phoneCaptcha'].config('sms_salt'))!==cookie('phone')){
                $this->error('手机号验证输入错误');
            }
            //写入数据库
            $memModel = new Member();
            if($memModel->allowField(true)->save($postData)){
                //每次入库完删除cookie中的phone值
                cookie('phone',null);
                $this->success("注册成功",url("/"));
            }else{
                $this->error("注册失败");
            }
        }
        return $this->fetch('');
    }


}