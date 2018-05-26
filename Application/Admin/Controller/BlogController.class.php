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
        if($data['blog_status'] == 9){
            $subject = '【十年之约】申请驳回通知！';
            $content = '<span style="display:none;">&nbsp;</span><style id="tmpl_style_notice_tmplid"> .default_text{margin-left:2px;margin-right:2px;background-color:#f5edcd;color:#949412;cursor:pointer;}
 .default_text:hover {background-color:#f5e3a3;}
 .default_text_hover{background-color:#f5e3a3;}
</style><div id="tmpl_module" style="background-image: url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png&quot;); background-position: 0px 0px; background-size: initial; background-repeat: repeat; background-attachment: initial; background-origin: initial; background-clip: initial;" tmpl_index_length="5" tmpl_background="url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png&quot;) 0px 0px"><div style="background-image: url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png&quot;); background-position: 0px 0px; background-size: initial; background-repeat: repeat; background-attachment: initial; background-origin: initial; background-clip: initial; font-size: 14px;" tmpl_background="url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png&quot;) 0px 0px"><div style="padding: 2em;"><div style="background-image: url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_line.png&quot;); background-position: 0px 0px; background-size: initial; background-repeat: repeat; background-attachment: initial; background-origin: initial; background-clip: initial; margin: 0px auto; padding: 0px 3em; line-height: 36px;" tmpl_background="url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_line.png&quot;) 0px 0px"><p id="test" style="background-color: initial; margin: 0px; line-height: 36px; font-size: 20px; text-align: center;">申请驳回通知</p><p style="margin: 0px; line-height: 36px;"><font color="#949412"><span style="background-color: rgb(245, 237, 205);">尊敬的 '.$data['blog_name'].'博主</span></font>：</p><p style="background-color: initial; margin: 0px; line-height: 36px; text-indent: 2em;">根据<span un="tmpl_default" tmpl_index="1">根据“十年公约”，所列条款，经过项目组审核评议论,决定驳回您的申请。</span></p><p style="background-color: initial; margin: 0px; line-height: 36px; text-indent: 2em;">驳回理由：您的站点目前暂不符合《十年公约》第二条中所列之内容。</p><p style="background-color: initial; margin: 0px; line-height: 36px; text-indent: 2em;">随本邮件附十年公约一份，您可仔细阅读，达到申请条件后可再行申请！<br></p><p style="background-color: initial; margin: 0px; line-height: 36px; text-indent: 2em;"></p><p style="background-color: initial; margin: 0px; line-height: 36px;">特此通知</p><p style="background-color: initial; margin: 0px; line-height: 36px; text-indent: 2em;"></p><div style="background-color: initial; text-align: right;"><p style=" margin:0;line-height:36px;">&nbsp;<span un="tmpl_default" tmpl_index="4">十年之约项目组</span></p><p style=" margin:0;line-height:36px;" un="date_text">'.date("Y年m月d日", time()).'</p><p style=" margin:0;line-height:36px;">&nbsp;</p><p style=" margin:0;line-height:36px;">&nbsp;</p></div><p style="background-color: initial; margin: 0px; line-height: 36px;">&nbsp;</p><p style="background-color: initial; margin: 0px; line-height: 36px;">&nbsp;</p><p style="background-color: initial; margin: 0px; line-height: 36px;">&nbsp;</p></div></div></div></div><!--<![endif]-->';
        }else{
            $subject = '【十年之约】欢迎加入十年之约！';
            $content = '<div style="background:url(\'//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png\') repeat 0 0;font-size:14px;" tmpl_background="url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_bg.png&quot;) 0px 0px">
    <div style="padding:2em;">
        <div style="margin:0 auto;padding:0 3em;line-height:36px;background:url(\'//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_line.png\') repeat 0 0;" tmpl_background="url(&quot;//exmail.qq.com/zh_CN/htmledition/images/compose_tmpl_line.png&quot;) 0px 0px">
            <p id="test" style=" margin:0;line-height:36px;font-size:20px;text-align:center;">审核通过通知</p>
            <p style=" margin:0;line-height:36px;">
            <span un="tmpl_default" tip="通知对象" tmpl_index="0">亲爱的十年之约成员</span>
            ：</p>
            <p style=" margin:0;line-height:36px;text-indent:2em;">
            <span un="tmpl_default" tmpl_index="1">很高兴通知您，您的申请已通过审核，欢迎加入十年之约！</span>
            </p>
            <p style=" margin:0;line-height:36px;text-indent:2em;">
            <span un="tmpl_default" tmpl_index="2">从今日起，您就是十年之约的正式成员！您收到本邮件之时，您博客的十年之约正式生效，请认真对待这个约定！</span>
            </p>
            <p style=" margin:0;line-height:36px;text-indent:2em;">
            <span un="tmpl_default" tmpl_index="2"></span>
            在
            <span un="tmpl_default" tmpl_index="3">约定期间，您的网站在涉及域名、名称等变化或暂时以及长期关闭等情况时，请到http://www.foreverblog.cn/ 留言，项目组将对此作更改或者记录！若有其它问题，请通过此邮箱与项目组取得联系！<br>
            </span>
            </p>
            <p style=" margin:0;line-height:36px;text-indent:2em;"></p>
            <p style=" margin:0;line-height:36px;">若您还有更多线上交流的需求，可加入十年之约线上QQ交流群（702409956）</p>
            <p style=" margin:0;line-height:36px;text-indent:2em;"></p>
            <div style="text-align:right;">
                <p style=" margin:0;line-height:36px;">&nbsp;
                <span un="tmpl_default" tmpl_index="4">十年之约项目组</span>
                </p>
                <p style=" margin:0;line-height:36px;" un="date_text">'.date("Y年m月d日", time()).'</p>
                <p style=" margin:0;line-height:36px;">&nbsp;</p>
                <p style=" margin:0;line-height:36px;">&nbsp;</p>
            </div>
            <p style=" margin:0;line-height:36px;">&nbsp;</p>
            <p style=" margin:0;line-height:36px;">&nbsp;</p>
            <p style=" margin:0;line-height:36px;">&nbsp;</p>
        </div>
    </div>
</div>';
        }
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