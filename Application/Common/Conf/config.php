<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL'=>2,//设置URL模式重写模式
	'DEFAULT_MODULE'=>'Home',//设置默认模块
	'MODULE_ALLOW_LIST'=>array('Home','Admin'),//设置允许访问的模块
	//增加自定义的模板替换配置信息
	'TMPL_PARSE_STRING'=>array(
		'__PUBLIC_ADMIN__'=>'/Public/Admin',
		'__PUBLIC_HOME__'=>'/Public/Home',
		),
	'SHOW_PAGE_TRACE'=>false,

	/* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'foreverblog',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sy_',    // 数据库表前缀

    // 配置邮件发送服务器
    'MAIL_SMTP'                     =>TRUE,
    'MAIL_HOST'                     =>'smtp.exmail.qq.com',//邮件发送SMTP服务器
    'MAIL_SMTPAUTH'                 =>TRUE,
    'MAIL_USERNAME'                 =>'notice@foreverblog.cn',//SMTP服务器登陆用户名
    'MAIL_PASSWORD'                 =>'',//SMTP服务器登陆密码
    'MAIL_SECURE'                   =>'ssl', //tls 端口25 ssl465
    'MAIL_PORT'                     =>'465', //tls 端口25 ssl465
    'MAIL_CHARSET'                  =>'utf-8',
    'MAIL_ISHTML'                   =>TRUE,

    'PUSH_BEAR_KEY' => '',

);