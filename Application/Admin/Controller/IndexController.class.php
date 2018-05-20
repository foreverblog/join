<?php
namespace Admin\Controller;

class IndexController extends CommonController {

    public function index(){
        $data = session('admin');
        $datainfo = M('Admin')->where('id='.$data['id'])->find();
        $this->assign('data',$datainfo);

        $nums = M('Admin')->count();
        $this->assign('adminnums',$nums); //管理员数量

        $Blognum = M('Bloginfo')->count();
        $this->assign('blognums',$Blognum); //申请数量

        $BlogSuccess = M('Bloginfo')->where('blog_status = 1')->count();
        $this->assign('blogsuccess',$BlogSuccess); //通过数量

        $BlogError = M('Bloginfo')->where('blog_status = 9')->count();
        $this->assign('blogerror',$BlogError); //未通过数量


        $Bloging = M('Bloginfo')->where('blog_status = 0')->count();
        $this->assign('bloging',$Bloging); //待审核数量

        $this->display();
    }

}