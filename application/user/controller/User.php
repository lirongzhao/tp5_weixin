<?php

namespace app\user\controller;

use app\common\model\Users;
use think\Controller;


class User extends Controller
{

    public function login(){
        return $this->fetch();
    }
  
    public function dologin(){

        //获取表单数据
        $condition=[];
        $condition['username']=input('username');
        $condition['password']=input('password');

//        $user=Users::where($condition)->find();
        $user=new Users();
        $uu=$user->where($condition)->find();

        if($uu){
            session('loginedUser',$uu->username);
            return $this->success('用户登录成功','/admin/upload');
        }else{
            return $this->error('用户名或密码错误');
        }
    }
    public function logout(){
        session('loginedUser',NULL);
        return $this->redirect('/user/user/login');
    }
    //修改密码；激活邮件功能；登录之后不能再登录
}
