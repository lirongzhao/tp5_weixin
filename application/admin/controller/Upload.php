<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/11/22
 * Time: 14:35
 */
namespace app\admin\controller;
use app\common\model\Uploadpts;
use think\Controller;
use think\Request;
use think\Session;
use weixin\wxCURL;
use weixin\wxMaterial;
use weixin\wxToken;
class Upload extends Controller
{

    public function index(){
        $u=Uploadpts::paginate(3);
        $this->assign('u',$u);
        return $this->fetch();


    }
    public function create(){
        return $this->fetch();
    }
    public function save(Request $request){
        $file=request()->file('photo');// 获取表单提交过来的文件
        $title=input('title');
        $author=input('author');
        $content=input('content');
        $error = $_FILES['photo']['error']; // 如果$_FILES['file']['error']>0,表示文件上传失败
        if ($error) {
            echo "<script>alert('文件上传失败！');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";// 返回上一页并刷新
        }
        //自定义素材上传本地路径
        $info = $file->move(ROOT_PATH . 'public/upload');
        //返回和入口文件同一级别的素材路径
        $n=$info->getSaveName();
        $path = str_replace('\\', '/', $n);
        $pp='/tp5_weixin/public/upload/'.$path;
        $p=ROOT_PATH.'/public/upload/'.$path;
        $type='thumb';
        //上传图片素材到微信服务器
        $m=new wxMaterial();
        $ret=$m->uploadMaterial($p,$type);
        $r=json_decode($ret,true);
//        dump($r);exit();
        //上传图文素材
        $ret=$r['media_id'];
        $rett=$r['url'];

        $postnews=[
            "articles"=> [
		        [
                    "thumb_media_id"=>$ret,
                    "author"=>$author,
			        "title"=>$title,
			        "content"=>$content,
                    "show_cover_pic"=>1
		        ]
            ]
        ];
        $news=new wxMaterial();
        $createnews=$news->createNews($postnews);
        $createnews=json_decode($createnews,true);
        $mid=$createnews['media_id'];
        $nss=$m->getNews($mid);
        $nes=json_decode($nss,true);
        $newss=$nes['news_item'][0];

//        Session::set('mid',$mid);
//        dump($mid);
        //推送图文消息
        $post=[
            "filter"=>[
                "is_to_all"=>true,
            ],
            "mpnews"=>[
            "media_id"=>$mid
            ],
            "msgtype"=>"mpnews",
            "send_ignore_reprint"=>1
        ];
        $postt=json_encode($post,JSON_UNESCAPED_UNICODE);
        $token=wxToken::getToken();
        $sendall_api='https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$token;
        $curl=new wxCURL();
        $c=$curl->post($sendall_api,$postt);
//        dump($c);exit();
        //保存到数据库
        $upload=new Uploadpts();
        $upload->title=input('title');
        $upload->author=input('author');
        $upload->content=input('content');
        $upload->url=$newss['url'];
        $upload->thumb_nurl=$pp;
        $upload->thumb_url=$newss['thumb_url'];
        $upload->media_id=$mid;
        if($upload->save()){
            return $this->success('图文消息上传成功','/admin/upload');
        }else{
            return $this->error('图文消息上传失败');
        }

    }
    public function edit($id){
        $this->assign('row',Uploadpts::get($id));
        return $this->fetch();
    }
    public function update(Request $request,$id){
        $news =Uploadpts::get($id);
        $news->photo=input('photo');
        $news->title=input('title');
        $news->content=input('content');
        if($news->save()){
            return $this->success('数据更新成功','/index/upload');
        }else{
            return $this->error('数据更新失败');
        }

    }
    public function delete($id){
        $news=Uploadpts::get($id);
        if($news->delete()){
            return  'ok';
        }else{
            return 'error';
        }
    }

}