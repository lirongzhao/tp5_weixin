<?php
namespace app\index\controller;


use think\Controller;

class Index extends Controller
{
    //填写微信公众号设置好的token
    private $token = 'zlrong';

    private $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
    private $imageTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image>
            <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>";
    private $voiceTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Voice>
            <MediaId><![CDATA[%s]]></MediaId>
            </Voice>
            </xml>";
    private $newsTpl="<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><ArticleCount>2</ArticleCount><Articles><item>
            <Title><![CDATA[%s]]></Title> <Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url></item><item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item></Articles></xml>";
    private $msgTpl='';
    private $msgType = 'text';
    //验证流程开始
    private function checkSignature()
    {
        $signature = request_data('get','signature');
        $timestamp = request_data('get','timestamp');
        $nonce = request_data('get','nonce');
        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    public function valid()
    {
        $echoStr = request_data('get','echostr');
        if($this->checkSignature()){
            exit($echoStr);
        }
    }
    //end
    public function index()
    {
        //$w = new Index;
        //$w->valid();
        //$w->responseMsg();
        $this->responseMsg();
    }
    public function responseMsg()
    {
        dump('responseMsg函数');
        $postStr = file_get_contents('php://input', 'r');
        dump($postStr);
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //把 PHP对象的变量转换成关联数组
            $wxmsg = get_object_vars($postObj);
            file_put_contents('msg-org.save', $postStr);
            //预处理方法进行消息处理
            $ret = $this->preMsgHandle($wxmsg);

            $mtpl = $this->msgTpl;
            $resultStr = sprintf(
                $this->$mtpl,
                $wxmsg['FromUserName'],
                $wxmsg['ToUserName'],
                time(),
                $this->msgType,
                $ret);
            file_put_contents('msg.save', $resultStr);
            exit($resultStr);
        } else {
            exit('success');
        }
    }
    private function textHandle($wxmsg){
        switch($wxmsg['Content'])
        {
            case '?':
                return $this->help();
                break;
            case 'help':
                return $this->help();
                break;
            case 'info':
                return 'programer';
                break;
            default:
                return $wxmsg['Content'];
        }
    }
    private function help()
    {
        return "enter help to get help doc\n" .
            "info to get my info.";
    }
    //消息预处理方法
    private function preMsgHandle($wxmsg)
    {

        //动态设置消息模板变量
        $this->msgTpl = $wxmsg['MsgType'] . 'Tpl';
        $this->msgType = $wxmsg['MsgType'];
        switch ($wxmsg['MsgType']) {
            case 'text':
                //文本类型直接返回消息内容
                return $this->textHandle($wxmsg);
                break;
            case 'voice':
                return $wxmsg['MediaId'];
                break;
            case 'image':
                return $wxmsg['MediaId'];
                break;
            case 'news':
                break;
            case 'video':
                //如果是视频消息，返回文本消息错误提示
                $this->msgTpl = 'textTpl';
                $this->msgType = 'text';
                return '该类型不被支持';
                break;
            case 'event':
                return $this->eventHandle($wxmsg);
                break;
            default: return 'null';
        }
    }
    //图文消息处理方法
    private function newsHandle($wxmsg){

    }
    //处理事件消息的方法
    private function eventHandle($wxmsg)
    {
        //保存事件信息
        $event_log = time() . " | " . $wxmsg['Event'];

        switch($wxmsg['Event'])
        {
            //页面跳转事件
            case 'VIEW':
                $event_log .= " | " . $wxmsg['EventKey'];
                break;
            //位置信息事件
            case 'LOCATION':
                $event_log .= " | lat<" .
                    $wxmsg['Latitude'] .
                    "> lng<" .
                    $wxmsg['Longitude'] .
                    ">";
                break;
            //关注公众号
            case 'subscrible':
                $event_log .= " | " .
                    $wxmsg['FromUserName'];
                break;
            //取消关注公众号
            case 'unsubscrible':
                break;
            //点击菜单返回消息事件
            case 'CLICK':
                $event_log .= $wxmsg['EventKey'];
                break;
            case 'SCAN':
                break;
            default: ;
        }
        $event_log .= "\n";
        file_put_contents('wx_event.log', $event_log,FILE_APPEND);
        $this->msgTpl = 'textTpl';
        return 'this is test info.';
    }
}



