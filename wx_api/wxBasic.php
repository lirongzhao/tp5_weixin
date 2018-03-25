<?php

namespace weixin;

class wxBasic
{

    static private $appid = 'wxd039ec2c1069150b';
    static private $secret = 'c289af796ad1651b4c47ff2f050b5bec';
    static private $self_token = 'zlrong';

    static public function getAppid()
    {
        return self::$appid;
    }

    static public function getSecret()
    {
        return self::$secret;
    }

    static public function getSelfToken()
    {
        return self::$self_token;
    }

    static public function getConfig()
    {
        return [
            'appid'=>self::$appid,
            'secret'=>self::$secret,
            'self_token'=>self::$self_token
        ];
    }

}
