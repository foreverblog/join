<?php
namespace Admin\Controller;

class BlogController extends CommonController
{
    public function index()
    {
        $data = M('Bloginfo')->select();
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 通过审核
     */
    public function adopt($id)
    {
        $data = array(
            'blog_status' => 1,
            'update_at' => time()
        );
        $res = M('Bloginfo')->where('id='.$id)->setField($data);
        if($res){
            $this->success('审核成功');
        }else{
            $this->error('审核失败');
        }
    }

    /**
     * 拒绝审核
     */
    public function refuse($id)
    {
        $data = array(
            'blog_status' => 9,
            'update_at' => time()
        );
        $res = M('Bloginfo')->where('id='.$id)->setField($data);
        if($res){
            $this->success('审核成功');
        }else{
            $this->error('审核失败');
        }
    }

    /**
     * 发送邮件
     */
    public function send($id)
    {
        $data = M('Bloginfo')->where('id='.$id)->find();
        $to = $data['blog_email'];
        $subject = '【十年之约】欢迎加入十年之约！';
        $content = '欢迎加入';
        if(sendMail($to,$subject, $content)){
            $res = M('Bloginfo')->where('id='.$id)->setField('is_mail',1);
            if($res){
                $this->success('发送成功');
            }else{
                $this->error('发送失败');
            }
        }else{
            $this->error('发送失败');
        }
    }

}