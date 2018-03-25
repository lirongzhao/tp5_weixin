<?php

namespace app\oauth\controller;

use app\common\model\Helps;
use think\Controller;
use think\Request;
use think\Session;

class Help extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
        public function index(){
           $help=Helps::where('openid',Session::get('openid'))->select();
           $view=Session::get('nickname');
           $this->assign('view',$view);
           $this->assign('h',$help);
           return $this->fetch();
        }
        public function create()
    {
        //
        if (Session::get('openid')) {
            return $this->fetch();
        } else {
            return $this->redirect('http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/oauth/oauthcode?state=help');
        }

    }




    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $help=new \app\common\model\Helps();
        $help->problem=input('problem');
        $help->openid=Session::get('openid');
        if($help->save()){
            $view=Session::get('nickname');

            $this->assign('view',$view);
            return $this->fetch('help/continue');

        }else{
            return '数据保存失败';
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
