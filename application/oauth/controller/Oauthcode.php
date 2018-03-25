<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/12/7
 * Time: 10:08
 */

namespace app\oauth\controller;

use think\Controller;
use think\Request;
use think\Session;
use weixin\wxBasic;
use weixin\wxCURL;

class Oauthcode extends Controller
{
    //获取公众号配置：appid，secret
    protected static $wxcfg;
    public function index()
    {

        //获取公众号配置：appid，secret
        self::$wxcfg=wxBasic::getConfig();
        //授权回调路径
        $return_url='http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/oauth/oauthreturn';
        //获取授权码接口
        $get_code_url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $get_code_url.='?appid='.self::$wxcfg['appid'] .
            '&redirect_uri='. urlencode($return_url) .
            '&response_type=code' .
            '&scope=snsapi_userinfo' .
            '&state='. input('get.state') .
            '#wechat_redirect';

        return $this->redirect($get_code_url);

//            exit($get_code_url);
//        header('location'.$get_code_url);
        //exit(input('get.state'));


    }
}