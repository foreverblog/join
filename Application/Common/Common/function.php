<?php
//邮件发送
function sendMail($to, $subject, $content)
{
    vendor('PHPMailer.class#phpmailer');
    $mail = new \PHPMailer(); //实例化
    // 装配邮件服务器
    if (C('MAIL_SMTP')) {
        $mail->IsSMTP();  //启动SMTP
    }
    $mail->Host = C('MAIL_HOST'); //SMTP服务器地址
    $mail->Port = C('MAIL_PORT'); //邮件端口
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用SMTP认证
    $mail->Username = C('MAIL_USERNAME');//邮箱名称
    $mail->Password = C('MAIL_PASSWORD');//邮箱密码
    $mail->SMTPSecure = C('MAIL_SECURE');
    $mail->CharSet = C('MAIL_CHARSET');//邮件头部信息
    $mail->From = C('MAIL_USERNAME');//发件人是谁
    $mail->AddAddress($to);
    $mail->FromName = '十年之约项目组';
    $mail->AddAttachment('./Public/10years.pdf','十年公约（2018年5月24日第二次修订）.pdf'); // 添加附件,并指定名称
    $mail->IsHTML(C('MAIL_ISHTML'));//是否是HTML字样
    $mail->Subject = $subject;// 邮件标题信息
    
    $mail->Body = $content;//邮件内容
    // 发送邮件
    if (!$mail->Send()) {
        return False;
    } else {
        return TRUE;
    }
}

//创建TOKEN
function creatToken() {
    $code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));
    session('TOKEN', authcode($code));
}

//判断TOKEN
function checkToken($token) {
    if ($token == session('TOKEN')) {
        session('TOKEN', NULL);
        return TRUE;
    } else {
        return FALSE;
    }
}

//加密TOKEN
function authcode($str) {
    $key = "ANDIAMON";
    $str = substr(md5($str), 8, 10);
    return md5($key . $str);
}

//获取Gravatar头像 QQ邮箱取用qq头像
function getGravatar($email, $s = 96, $d = 'mp', $r = 'g', $img = false, $atts = array())
{
    preg_match_all('/((\d)*)@qq.com/', $email, $vai);
    if (empty($vai['1']['0'])) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
    }else{
        $url = 'https://q2.qlogo.cn/headimg_dl?dst_uin='.$vai['1']['0'].'&spec=100';
    }
    return  $url;
}

/**
 * 获取IP
 * @param int $type
 * @return mixed
 */
function getClientIp($type = 0) {
    $type       =  $type ? 1 : 0;
    $ip         =   'unknown';
    if ($ip !== 'unknown') return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实 IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的 ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的 ip 地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP 地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}