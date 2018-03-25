<?php
require '../vendor/autoload.php';

//调用wxToken类获取token
$token=weixin\wxToken::getToken();

echo $token;

if(false=== $token){

}