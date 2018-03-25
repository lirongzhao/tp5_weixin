<?php

namespace app\admin\controller;

use app\common\model\Helps;
use think\Controller;
use think\Request;
class Help extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
      // public function test()
    // {
    //     //使用缓存
    //     //首先从缓存中获取数据（）
    //     $users=Cache::get('users');
    //     if(!$users){
    //         //缓存中没有指定数据
    //         //从数据库中获取
            
    //         $users=Db::table('users')->select();
    //         //把数据库数据写入cache中
    //         Cache::set('users',$users);
    //     }
    //    
    // }
    protected $fields=['status','uid'];
    public function index()
    {
       
        $help=Helps::paginate(8);
        $this->assign('view',$help);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
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
        $show=Helps::where('id',$id)->find();
        $this->assign('row',$show);
        return $this->fetch();
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
        $reply=input('reply');
        $data=array('status'=>1,'reply'=>$reply);
        $return=Helps::where('id',$id)->setField($data);
        if($return){
            return $this->success('数据更新成功','/admin/help');
        }else{
            return  $this->error('数据更新失败');
        }
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
