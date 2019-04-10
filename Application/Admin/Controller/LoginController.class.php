<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {

    public function login()
    {
        if(IS_POST){
            //接受验证码比对
            $captcha = I('post.captcha');
            $verify = new \Think\Verify();
            $res = $verify ->check($captcha);
            if(!$res){
                $this->error('验证码不正确');
            }
            //接受用户密码调用模型方法比对
            $username = I('post.username');
            $password = I('post.password');
            //根据用户名查询用户信息
            $userinfo = M('Admin')->where("username = '%s ' ",$username)->find();
            if(!$userinfo){
                $this->error('用户名不存在');
            }
            //根据密码进行比对
            if($userinfo['password'] != md5(md5($password))){
                $this->error('密码错误');
            }
            //说明该用户信息是正确的可以登录
            //保存用户的登录状态
            session('admin',$userinfo);
            if(!$userinfo){
                $this->error('登录失败');
            }
            $ip = getClientIp(); //获取登录ip
            $data = array(
                'land_num' => $userinfo['land_num'] + 1,
                'last_ip' => $userinfo['now_ip'],
                'last_date' => $userinfo['now_time'],
                'update_at' => time(),
                'now_time' => time(),
                'now_ip' => $ip
            );
            $res = M('Admin')->where("username = '%s ' ",$username)->setField($data);
            if($res){
                $this->success('登陆成功',U('Index/index'));
            }else{
                $this->error('登陆失败');
            }
        }else{
            $this->display();
        }
    }

    public function logout()
    {
        session('admin',null);
        $this->success('已成功退出后台!',U('Login/login'));
    }

    //生成验证码
    public function verify()
    {
        $config = array('length'=>4);
        $verify = new \Think\Verify($config);
        $verify->entry();
    }

}