<?php
/**
 * Created by PhpStorm.
 * user: lucky~丽
 * Date: 2017/11/22
 * Time: 19:10
 */

namespace app\common\model;


use think\Model;
use traits\model\SoftDelete;

class Uploadpts extends Model
{
    //默认情况下，当前模型类对应的数据表是模型类的名称
    protected $table='Uploadpts';//当前操作的数据表名称
    //是否开启自动维护时间戳
    protected $autoWriteTimestamp='datetime';//使用timestopm类型
    protected $createTime='create_time';//插入记录时，自动维护的字段
    protected $updateTime='update_time';
    protected $deleteTime='delete_time';//软删除字段，时间戳
    use SoftDelete;
}