<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/12/7
 * Time: 10:09
 */

namespace app\oauth\controller;

use think\Controller;
use app\common\model\Userinfos;
use weixin\wxBasic;
use weixin\wxCURL;
use think\Session;
class Oauthreturn extends Controller
{
    protected static $wxcfg;
    public function index()
    {
        self::$wxcfg=wxBasic::getConfig();
        $code=isset($_GET['code'])?$_GET['code']:'';
        if (empty($code)) {
            exit('Error: code is empty!');
        }

        //获取网页授权access_token接口
        $oauth_token_api = 'https://api.weixin.qq.com/sns/oauth2/access_token'.
            '?appid=' . self::$wxcfg['appid'] .
            '&secret=' . self::$wxcfg['secret'] .
            '&code=' . $code .
            '&grant_type=authorization_code';
        //初始化curl对象并调用access_token接口
        $wxcurl=new wxCURL();
        $response=$wxcurl->get($oauth_token_api);
        //对返回值进行解码
        $oauth_token = json_decode($response,true);

        //如果access_token获取失败则退出
        if (!isset($oauth_token['access_token'])) {
            exit('token获取失败');
        }

        //获取用户信息接口
        $oauth_info_api = 'https://api.weixin.qq.com/sns/userinfo' .
            '?access_token=' . $oauth_token['access_token'] .
            '&openid='.$oauth_token['openid'] .
            '&lang=zh_CN';

        $response = $wxcurl->get($oauth_info_api);

        //解码json到PHP关联数组
        $info = json_decode($response,true);

        $view=[];

        //判断是否成功获取信息。失败则退出并提示错误信息
        if (isset($info['errcode'])) {
            $this->assign('','获取用户信息失败.<br>' . $info['errmsg']);
            return $this->fetch('userinfo/oauth_userinfo_error');
            exit();
        }
        Session::set('openid',$info['openid']);
        Session::set('nickname',$info['nickname']);
       $a=$this->save($info);

        switch (input('get.state')){
            case 'user':
                $view=[];
                $view['nickname'] = $info['nickname'];
                $view['where'] = $info['country'].$info['province'].$info['city'];
                $view['openid'] = $info['openid'];
                $view['headimgurl']=$info['headimgurl'];

                $this->assign('view',$view);
                return $this->fetch('userinfo/oauthuserinfo');
                break;
            case 'help':
                return $this->fetch('help/create');
                break;
            case 'query':
                return $this->fetch('query/index');
                break;
        }
//        //记录用户信息到数据库

    }
    public function save($info){

//        $old=Userinfos::where('openid',$info['openid'])->find();
      $old=Userinfos::where('openid',$info['openid'])->find();

        if(!$old){
            $view=new Userinfos();
            //设置页面变量
            $view->nickname = $info['nickname'];
            $view->sex = $info['sex'];
            $view->where = $info['country'].$info['province'].$info['city'];
            $view->openid = $info['openid'];
            if ($info['headimgurl']) {
                $view->headimgurl= $info['headimgurl'];
            }

            if($view->save()){
               return '数据插入成功';
            }else{
              return  '数据插入失败';
            }
        }
        return '用户已存在';
    }
}