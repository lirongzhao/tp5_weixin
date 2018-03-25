<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/11/27
 * Time: 10:55
 */

namespace app\common\model;


use think\Model;

class Mediatype extends Model
{
    //默认情况下，当前模型类对应的数据表是模型类的名称
    protected $table='Mediatype';//当前操作的数据表名称
    //一个类型有多个素材
    public function medias(){
        return $this->hasMany('materials','tid');
    }
}