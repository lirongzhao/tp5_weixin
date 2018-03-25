<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/11/22
 * Time: 9:23
 */

namespace app\index\controller;
use think\Controller;
use weixin\wxMenu;


class Menu extends Controller
{
    private $m,$menu;
    private $post_menu=[

        'button'=> [
           [
               'type'=>'view',
               'name'=>'新闻列表',
               'url'=>'http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/news/news'
           ],
            [
                'name'=>'用户',
                'sub_button'=>[
                    [
                        'type'=>'view',
                        'name'=>'用户中心',
                        'url'=>'http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/oauth/userinfo',

                    ],
                    [
                        'type'=>'view',
                        'name'=>'获取帮助',
                        'url'=>'http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/oauth/help' ,
                    ],
                    [
                        'type'=>'view',
                        'name'=>'问题查询',
                        'url'=>'http://zlrong.wywwwxm.com/tp5_weixin/public/index.php/oauth/query/create' ,
                    ],


                ]
            ]
        ]
    ];
    public function index(){
        $this->m=new wxMenu();
        $this->menu=$this->m->createMenu($this->post_menu);
        echo $this->menu;
        //return $this->menu;

    }

    public function getMenu(){
        $this->m=new wxMenu();
        $this->menu=$this->m->getMenu();
        return $this->menu;

    }
    public function deleteMenu(){
        $this->m=new wxMenu();
        $this->menu=$this->m->deleteMenu();
        return $this->menu;
    }

}