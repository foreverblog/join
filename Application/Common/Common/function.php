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
    $mail->SMTPSecure = C('MAIL_SECURE');//发件人地址
    $mail->CharSet = C('MAIL_CHARSET');//邮件头部信息
    $mail->From = C('MAIL_USERNAME');//发件人是谁
    $mail->AddAddress($to);
    $mail->FromName = '十年之约项目组';
    $mail->AddAttachment('./Public/test.png','十年公约.png'); // 添加附件,并指定名称
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

/* 加密TOKEN */
function authcode($str) {
    $key = "ANDIAMON";
    $str = substr(md5($str), 8, 10);
    return md5($key . $str);
}