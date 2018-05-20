<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {

    public function __construct()
    {
        parent::__construct();
        //判断当前用户是否登录
        $admin = session('admin');

        if(!$admin){
            $this->error('没有登录',U('Login/login'));
        }
    }

    //空操作
    public function _empty()
    {
        redirect('/admin');
    }
}
