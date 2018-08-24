<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    /**
     * 提交页
     */
    public function index()
    {
        if (IS_GET) {
            //创建token
//            creatToken();
            $this->display();
        } else {
//            if (!checkToken($_POST['TOKEN'])) {
//                $this->ajaxReturn(array('status' => 2, 'msg' => '皮一下你很快乐吗？'));
//            }
            //接受验证码比对
            $captcha = I('post.captcha');
            $verify = new \Think\Verify();
            $res = $verify ->check($captcha);
            if(!$res){
                $this->ajaxReturn(array('status' => 0, 'msg' => '验证码不正确'));
            }
            $blogname = I('post.blogname');
            $blogurl = I('post.blogurl');
            $email = I('post.email');
            $send_word = I('post.send_word');
            $memorabilia = I('post.memorabilia');
            if(!preg_match("/^(http:\/\/|https:\/\/).*$/",$blogurl)){
                $this->ajaxReturn(array('status' => 0, 'msg' => '网站地址错误'));
            }
            if(!preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$email)){
                $this->ajaxReturn(array('status' => 0, 'msg' => '邮箱错误'));
            }
            $imgUrl = getGravatar($email);
            $model = M('Bloginfo');
            $url = $model->where("blog_url= '%s'",$blogurl)->order('id desc')->find();
            if(($url != null) && ($url['blog_status'] == 0)){
                $this->ajaxReturn(array('status' => 0, 'msg' => '已经提交过申请，请耐心等待'));
            }elseif($url['blog_status'] == 9){
                $data = array(
                    'blog_name' => htmlspecialchars($blogname),
                    'blog_url' => htmlspecialchars($blogurl),
                    'blog_email' => htmlspecialchars($email),
                    'send_word' => htmlspecialchars($send_word),
                    'memorabilia' => htmlspecialchars($memorabilia),
                    'create_at' => time(),
                    'blog_imgurl' => $imgUrl
                );
                $res = $model->add($data);
                if ($res == false) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => '提交失败'));
                }
                $this->ajaxReturn(array('status' => 1, 'msg' => '提交成功'));
            }else{
                $data = array(
                    'blog_name' => htmlspecialchars($blogname),
                    'blog_url' => htmlspecialchars($blogurl),
                    'blog_email' => htmlspecialchars($email),
                    'send_word' => htmlspecialchars($send_word),
                    'memorabilia' => htmlspecialchars($memorabilia),
                    'create_at'=> time(),
                    'blog_imgurl' => $imgUrl
                );
                $res = $model->add($data);
                if ($res == false) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => '提交失败'));
                }
                $this->ajaxReturn(array('status' => 1, 'msg' => '提交成功'));
            }

        }
    }

    /**
     * 邮箱头像
     * @param $email
     * @param int $s
     * @param string $d
     * @param string $r
     * @param bool $img
     * @param array $atts
     * @return string
     */
    public function getGravatar($email, $s = 96, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    //生成验证码
    public function verify()
    {
        $config = array('length'=>4);
        $verify = new \Think\Verify($config);
        $verify->entry();
    }

    /**
     * 提交成功页
     */
    public function successfully()
    {
        $this->display();
    }

    /**
     * 恶意提交
     */
    public function errorfully()
    {
        Header('Location:http://www.foreverblog.cn/');
    }

    /**
     * 防止修改http和https重复提交
     * @param $url
     * @return null|string|string[]
     */
    public static function getUrl($url)
    {
        if (preg_match('/(http:\/\/)|(https:\/\/)/i', $url)) {
            // 去掉https://和http://前缀
            $newurl = preg_replace('/(http:\/\/)|(https:\/\/)/i', '', $url);
            return $newurl;
        }
    }
}