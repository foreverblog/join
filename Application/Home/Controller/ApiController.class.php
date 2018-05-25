<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller
{
    public function toUrl()
    {
        $email = I('get.email');
        $imgUrl = getGravatar($email);
        echo $imgUrl;
    }

}