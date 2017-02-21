<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/2/21
 * Time: 22:35
 */
defined('WEKIT_VERSION') or exit(403);

class TopNavHook
{
    public function show($loginUser)
    {
        $left = $this->_navDao()->getList(20, 0, "float_type=1");
        $right = $this->_navDao()->getList(20, 0, "float_type=2");
        $left_html = $right_html = '';

        foreach ($left as $v) {
            $left_html .= $v['a'];
        }
        foreach ($right as $v) {
            $right_html .= $v['a'];
        }


        $uid = $loginUser->uid;
        $username = Pw::substrs($loginUser->username, 6);
        $login_url = WindUrlHelper::createUrl('u/login/run');
        $register_url = WindUrlHelper::createUrl('u/register/run');
        $space_url = WindUrlHelper::createUrl('space/index/run?uid=' . $uid);
        $profile_url = WindUrlHelper::createUrl('profile/index/run?uid=' . $uid);
        $message_url = WindUrlHelper::createUrl('message/message/run?uid=' . $uid);
        $logout_url = WindUrlHelper::createUrl('u/login/logout');

        if (!$loginUser->isExists()) {
            $header_html = <<<EOF
            <div class="header_login">
                <a rel="nofollow" href="{$login_url}">登录</a><a rel="nofollow" href="{$register_url}">注册</a>
            </div>
EOF;
        } else {
            $header_html = <<<EOF
            <a  href="{$space_url} "class="username" title="{$username}">{$username}</a>
            <a  href="{$profile_url}"> 设置 </a>
            <a href="{$message_url}"> 消息 </a>
            <a href="{$logout_url}"> 退出 </a>
EOF;
        }

        $html = <<<EOD
<style>
    .m-top-nav {
        width: 1260px;
        margin: 5px auto;
        height: 20px;
    }
    
    .m-top-nav a {
        color: #333;
        text-decoration: none;
        margin-right: 5px;
    }
    
    .left {
        float: left;
    }
    
    .right {
        float: right;
    }
    
    .m-top-nav .m-login {
        float: left;
    }

    .m-top-nav .username {
        font-weight: blod;
        color: #000;
    }
    
    .m-top-nav .header_login {
        margin: 0;
        padding: 0;
        float: left;
        margin-right: 5px;
    }
    
    .m-top-nav .header_login_later,
    .m-top-nav .header_login_btn {
        padding: 0;
        margin-top: 0;
    }
</style>
<div class="m-top-nav">
    <div class="left">
        {$left_html}
    </div>
    <div class="right">
        <div class="m-login">
         {$header_html}
        </div>
        {$right_html}
    </div>
</div>
EOD;
        echo $html;

    }


    protected function _navDao()
    {
        return Wekit::load('SRC:extensions.mTopNav.service.dao.App_MTopNav_MTopNavDao');
    }
}