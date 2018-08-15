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
            $ip = get_client_ip();
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
                    'blog_imgurl' => $imgUrl,
                    'user_ip'=>$ip
                );
                $res = $model->add($data);
                if ($res == false) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => '提交失败'));
                }
                $this->sendPushBear($blogname,$blogurl,$email,$ip);
                $this->ajaxReturn(array('status' => 1, 'msg' => '提交成功'));
            }else{
                $data = array(
                    'blog_name' => htmlspecialchars($blogname),
                    'blog_url' => htmlspecialchars($blogurl),
                    'blog_email' => htmlspecialchars($email),
                    'send_word' => htmlspecialchars($send_word),
                    'memorabilia' => htmlspecialchars($memorabilia),
                    'create_at'=> time(),
                    'blog_imgurl' => $imgUrl,
                    'user_ip'=>$ip
                );
                $res = $model->add($data);
                if ($res == false) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => '提交失败'));
                }
                $this->sendPushBear($blogname,$blogurl,$email,$ip);
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
     * 推送微信消息
     */
    public function sendPushBear($blogname,$blogurl,$email,$ip)
    {
        $url = 'https://pushbear.ftqq.com/sub';
        $text = $blogname.'申请加入十年之约';
        $desp = "### 博客名称：".$blogname."\n\n### 博客链接：[".$blogurl."](".$blogurl.")"."\n\n### 博主邮箱：".$email."\n\n### 申请IP：".$ip."\n\n### 请审核人员注意审核~辛苦啦";
        $param = array(
            'sendkey'=>C(PUSH_BEAR_KEY),
            'text' => $text,
            'desp' => $desp
        );
        //将数组拼接成url地址参数
        $paramurl = http_build_query($param);
        self::myCurl($url,$paramurl);
    }

    public static function myCurl($url,$params=false){
        $ch = curl_init();     // Curl 初始化
        $timeout = 30;     // 超时时间：30s
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);      // Curl 请求有返回的值
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     // 设置抓取超时时间
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);        // 跟踪重定向
        curl_setopt($ch, CURLOPT_ENCODING, "");    // 设置编码
        curl_setopt($ch, CURLOPT_REFERER, $url);   // 伪造来源网址
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); // 取消gzip压缩
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
        $content = curl_exec($ch);
        curl_close($ch);    // 结束 Curl
        return $content;    // 函数返回内容
    }
}