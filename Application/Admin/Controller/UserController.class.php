<?php
namespace Admin\Controller;

class UserController extends CommonController {

    public function index()
    {
        $data = M('Admin')->select();
        $adminnum = M('Admin')->count();
        $this->assign('data',$data);
        $this->assign('adminnum',$adminnum);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $nickname = I('post.adminName');
            $username = I('post.username');
            $password = I('post.password');
            $repassword = I('post.repassword');
            $level = I('post.adminRole');
            $userinfo = M('Admin')->where("username = '%s ' ",$username)->find();
            if($username === $userinfo['username']){
                $this->error($username.'用户已存在，请重新输入');
            }
            $password = md5(md5($password));
            $data = array(
                'username' => $username,
                'password' => $password,
                'level' => $level,
                'nickname' => $nickname,
                'create_at' => time(),
                'update_at' => time()
            );
            $res = M('Admin')->add($data);
            if($res){
                $this->success($username.'用户添加成功');
            }else{
                $this->error($username.'用户添加失败');
            }
        }else{
            $this->display();
        }
    }

    public function del($id)
    {
        $res = M('Admin')->where('id='.$id)->delete();
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function edit($id)
    {
        if(IS_POST){
            $nickname = I('post.adminName');
            $username = I('post.username');
            $password = I('post.password');
            $repassword = I('post.repassword');
            $level = I('post.adminRole');
            $password = md5(md5($password));
            $data = array(
                'username' => $username,
                'password' => $password,
                'level' => $level,
                'nickname' => $nickname,
                'update_at' => time()
            );
            $res = M('Admin')->where('id='.$id)->save($data);
            if($res){
                $this->success('用户修改成功');
            }else{
                $this->error('用户修改失败');
            }

        }else{
            $data = M('Admin')->where('id='.$id)->find();
            $this->assign('data',$data);
            $this->display();
        }
    }
}