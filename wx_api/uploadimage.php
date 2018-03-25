<?php
require '../vendor/autoload.php';

//获取access_token
$token=weixin\wxToken::getToken();

if(false===$token){
    exit('Error:get token failed');
}

//设置上传图片api
$upload_image_api='https://api.weixin.qq.com/cgi-bin/materials/add_material?access_token='.$token.'&type=image';

//
$ch=curl_init();
$file=new CURLFile('../image/1234.jpg');
//
$curl_options=[
    CURLOPT_SSL_VERIFYPEER=>false,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_POST=>true,
    CURLOPT_POSTFIELDS=>['materials'=>$file],
    CURLOPT_URL=>$upload_image_api
];

curl_setopt_array($ch,$curl_options);

//运行请求
$ret=curl_exec($ch);

//保存返回结果到文件
file_put_contents('upload_media_ret.save',$ret);

//关闭curl
curl_close($ch);
echo $ret;