<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller
{
    public function toUrl()
    {
        $email = I('get.email');
        $imgUrl = $this->getGravatar($email);
        echo $imgUrl;
    }

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
}