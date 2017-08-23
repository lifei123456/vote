<?php
namespace Home\Logic;

class WxResponseLogic{
    private $token;
    private $access_token;

    public function __construct() {
        $this->token = "lucktoken";
        $this->getAccessToken();
    }

    public function valid() {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }


    //等待配置
    private function getAccessToken() {
        $this->access_token = file_get_contents("http://115.29.112.114/wxJssdk/JssdkInterface.php?type=access_token_web");
    }

    //更新开发者接入微信公众号的accessTone
    public function updateAccessToken() {
        $this->access_token = file_get_contents("http://115.29.112.114/wxJssdk/JssdkInterface.php?type=update_access_token");
    }


//    private function getAccessToken() {
//        $this->access_token = file_get_contents("http://ancai4399.com/jssdk_server/JssdkInterface.php?type=access_token_web");
//    }
//
//    //更新开发者接入微信公众号的accessTone
//    public function updateAccessToken() {
//        $this->access_token = file_get_contents("http://ancai4399.com/jssdk_server/JssdkInterface.php?type=update_access_token");
//    }

    private function checkSignature() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function chatBack() {
        if (isset($_GET['echostr']))
        {
            $this->valid();
        }
        else {
            $postDate = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents("php://input");
            $object= simplexml_load_string($postDate,"SimpleXMLElement",LIBXML_NOCDATA);
            $arr['openid']=$object->FromUserName;
            $arr['mediaid']=$object->ToUserName;
            $arr['MsgType']=$object->MsgType;
            $arr['Event']=$object->Event;
            if($object->Event == "user_get_card") {
                $arr['code']= $object->UserCardCode ;
                $arr['cardId']= $object->CardId ;
            }
            else if($object->MsgType = "text") {
                $arr['Content']=$object->Content;
            }
            return $arr;
        }
    }

    public function chatBackForInterface(){
        if (isset($_GET['echostr']))
        {
            $this->valid();
        }
        else {
            $postDate = $GLOBALS["HTTP_RAW_POST_DATA"];
            $object= simplexml_load_string($postDate,"SimpleXMLElement",LIBXML_NOCDATA);
            return $object;
        }
    }

    public function getBasicInfo($openId) {
        //尝试两次，自动获取微信基本信息，失败则直接返回空
        $info=$this->getInfo($openId,$this->access_token);
        if($info['errcode']==40001) {
            $this->updateAccessToken();
            $info=$this->getInfo($openId,$this->access_token);
        }
        return $info;
    }

    private function getInfo($openId,$access_token){
        //https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=o16hwwb9HjRJ9uxDHWqd4FoHdeFI&lang=zh_CN
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openId&lang=zh_CN";
        $res = file_get_contents($url); //获取文件内容或获取网络请求的内容
        $result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
        return $result;
    }


    public function back($openId,$publicId,$info){
        $replyXml = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[text]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";
        $resultStr = sprintf($replyXml,$openId,$publicId,time(),$info);
        echo $resultStr;die;
    }

    public function test($openId,$publicId,$info){
        $replyXml = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[text]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";
        $resultStr = sprintf($replyXml,$openId,$publicId,time(),$info);
        echo $resultStr;die;
    }

    public function error($openId,$publicId){
        $replyXml = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[text]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";
        $resultStr = sprintf($replyXml,$openId,$publicId,time(),"请重新点击菜单,若多次失败请联系管理人员!");
        echo $resultStr;die;
    }


    /*
            {
       "touser":"OPENID",
       "msgtype":"news",
       "news":{
           "articles": [
            {
                "title":"Happy Day",
                "description":"Is Really A Happy Day",
                "url":"URL",
                "picurl":"PIC_URL"
            },
            {
                "title":"Happy Day",
                "description":"Is Really A Happy Day",
                "url":"URL",
                "picurl":"PIC_URL"
            }
            ]
       }
   }
            * */
    public function kefuMsg($openid) {
        $requestUrl="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$this->access_token";
        $createCardPostJson='{
            "touser":"'.$openid.'",
                "msgtype":"news",
                "news":{
                        "articles": [
                     {
                         "title":"恭喜你中奖了",
                         "description":"惊喜不惊喜",
                         "url":"'.sprintf(C("userinfoUrl"),C("appid"),urlencode(U("Home/Index/detail","",false,true)),getCurrentVersion()).'",
                         "picurl":"http://wx.aufe.vip/luck/Public/img/back.jpg"
                     },
                     ]
                }
            }';
        return $this->curlHttpRequest($requestUrl,null,true,$createCardPostJson);
    }


    public function kefuMsg_text($openid,$text) {
        $requestUrl="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$this->access_token";
        $createCardPostJson='{
            "touser":"'.$openid.'",
                "msgtype":"text",
                "text":
                {
                    "content":"'.$text.'"
                }
            }';
        return $this->curlHttpRequest($requestUrl,null,true,$createCardPostJson);
    }

    public function curlHttpRequest($url,$cookie = null,$skipssl = false ,$postDate = "") {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        if(! empty($cookie)) {
            curl_setopt($ch , CURLOPT_COOKIE , $cookie);
        }
        if( $skipssl) {
            curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false);
            curl_setopt($ch , CURLOPT_SSL_VERIFYHOST , 0);
        }
        if( ! empty($postDate)) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($ch ,CURLOPT_POST ,1);
            curl_setopt($ch ,CURLOPT_POSTFIELDS ,$postDate );
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        curl_close($ch);
        return $tmpInfo;
    }

}


?>