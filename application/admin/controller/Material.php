<?php

namespace app\admin\controller;

use app\common\model\Mediatype;
use think\Controller;
use think\Request;
use app\common\model\Materials;
use weixin\wxMaterial;

class Material extends Controller

{

    public function index()
    {
        $m=Materials::paginate(3);
        $this->assign('m',$m);
        return $this->fetch();

    }
    public function create()
    {
        return $this->fetch();
    }
    public function save(Request $request)
    {
        $file=request()->file('file');// 获取表单提交过来的文件
        $error = $_FILES['file']['error']; // 如果$_FILES['file']['error']>0,表示文件上传失败
        if ($error) {
            echo "<script>alert('文件上传失败！');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";// 返回上一页并刷新
        }
        //自定义素材上传本地路径
        $info = $file->move(ROOT_PATH . 'public/upload');

        //返回和入口文件同一级别的素材路径
        $n=$info->getSaveName();
        $path = str_replace('\\', '/', $n);
        $p='/upload/'.$path;
        $pp=ROOT_PATH.'/public/upload/'.$path;
        $type=input('type');
        if($type=='video'){
            $introduction=input('introduction');
            $post=[
                "title"=>'111',
                "introduction"=>$introduction
            ];
            $postt=json_encode($post,JSON_UNESCAPED_UNICODE);
        }else{
            $postt='';
        }
        $m=new wxMaterial();
        $ret=$m->uploadMaterial($pp,$type,$postt);
        $r=json_decode($ret,true);

        $materials=new Materials();
        $materials->type=$type;
        $materials->media_id=$r['media_id'];
        $materials->nurl=$p;
        $materials->introduction=input('introduction');
        if($materials->type == 'image' || $materials->type=='thumb'){
            $materials->url=$r['url'];
        }else{
            $materials->url='';
        }
        if($materials->save()){

            return $this->success('数据插入成功','/admin/material');
        }else{
            return $this->error('数据插入失败');
        }



    }
    public function read($id)
    {

    }
    public function delete($id)
    {
        //echo 'delete-'.$id;
        $news=Materials::get($id);
        $media_id=$news->media_id;

        $materials=new wxMaterial();
        $del=$materials->removeMaterial($media_id);
        if($news->delete()){
            return 'ok';
        }else{
            return 'error';
        }
    }
}
