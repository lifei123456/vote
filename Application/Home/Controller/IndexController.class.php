<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
//        file_put_contents("./Public/resource/conf.json",json_encode(array(
//            "version"=>1,
//            "returnText"=>"您的抽奖代码是%s,返回链接为<a href='%s'>返回链接</a>"
//        ),JSON_UNESCAPED_UNICODE));
//        die;
//        die(U("Home/Index/index","",false));

        /*
         * 新用户抽奖；
         * 检测逻辑如下：
         * 1、获取该用户的openid和mediaid
         * 2、查询该用户于user和code两张表是否存在记录，具体做法为获取该用户的基本信息，再先查询user是否存在该记录，
         * 根据获取的基本信息查询code中是否已经于上传分享图片过程中实现过抽奖逻辑
         * 3、根据上一步的查询结果，如果code中存在该用户的抽奖记录，直接获得该luckcode并拼接相应的url返回给用户，
         * 如果没有记录，插入记录，对于user表也是相同（先于code表执行），返回相关的xml
         *
         * 咳咳，整体逻辑比较简单，就不专门写一个Logic了
         * */
//        file_put_contents("test.txt","test");
        $wxResponse = D("WxResponse","Logic");
        $res = $wxResponse->chatBack();
//        if($res['Content'] != "抽奖") {
//            $wxResponse->back($res['openid'],$res['mediaid'],"我是SA的弟弟SB！");
//        }
        $confRes = json_decode(file_get_contents("./Public/resource/conf.json"),true);
        $version = $confRes['version'];
        $returnText=str_replace(array("&lt;","&gt;"),array("<",">"),$confRes['returnText']);
        if(! empty($res['openid']) ) {
            $info = $wxResponse->getBasicInfo($res['openid']);
//            file_put_contents("test.txt",json_encode($info).PHP_EOL,FILE_APPEND);
//            file_put_contents("test.txt",$res['openid'].PHP_EOL,FILE_APPEND);
            $user=D("User"); $code=D("Code");
            if(! $user->isExist(array(
                'unionid'=>$info['unionid']
            ))) {
                $addArr = array(
                    'unionid'=>$info['unionid'],
                    'openid'=>$info['openid'],
                    'time'=>time(),
                    'nickname'=>$info['nickname'],
                    'other_info'=>json_encode($info,JSON_UNESCAPED_UNICODE)
                );
                if(!$user->addNew($addArr)) {
                    $wxResponse->back($res['openid'],$res['mediaid'],"EOF1");
                }
            }
            if( $code->isExist(array(
                'unionid'=>$info['unionid'],
                'version'=>$version
            ))) {
                //需要设置该 luckcode 的更新时间为当前时间
                if( !$code->update(array('unionid'=>$info['unionid'],'version'=>$version ) , array('time'=>time()) )) {
                    $wxResponse->back($res['openid'],$res['mediaid'],"EOF2");
                }
                $result = $code->getOneInfo(array(
                    'unionid'=>$info['unionid'],
                    'version'=>$version
                ));
            }
            else {
                $result=array(
                    'unionid'=>$info['unionid'],
                    'luckcode'=>random(),
                    'time'=>time(),
                    'version'=>$version
                );
                if(!$code->addNew($result)) {
                    $wxResponse->back($res['openid'],$res['mediaid'],"EOF3");
                }
            }
            //这是参与活动的链接，不需要表现出链接的版本性，活动一定是当前最新的
            $url=sprintf(C("userinfoUrl"),c("appid"),urlencode(U("Home/Index/luck","",false,true)),"");
//            $wxResponse->back($res['openid'],$res['mediaid'],$result['luckcode']);
            $wxResponse->back($res['openid'],$res['mediaid'],sprintf($returnText,$result['luckcode'],$url));
        }
        else {
            die("EOF");
        }

//        $this->show();  JSON_UNESCAPED_UNICODE

//        file_put_contents("./Public/resource/conf.json",json_encode(array("version"=>1)));
//        echo sprintf(C("userinfoUrl"),c("appid"),urlencode("http://wx.aufe.vip/luck/index.php?s=Home/Index/luck"),"");die;
        $base64='data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABHAAD/4QODaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzEzMiA3OS4xNTkyODQsIDIwMTYvMDQvMTktMTM6MTM6NDAgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6MDBkNWRkMzctNzdmZS00MjY5LTg0MWEtN2Q4MGVlODVkMTBjIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjA2RUYyOUNBRDRBMzExRTY4QUM3OTlDMkEzRTJDQUU0IiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjA2RUYyOUM5RDRBMzExRTY4QUM3OTlDMkEzRTJDQUU0IiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE1LjUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowMGQ1ZGQzNy03N2ZlLTQyNjktODQxYS03ZDgwZWU4NWQxMGMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDBkNWRkMzctNzdmZS00MjY5LTg0MWEtN2Q4MGVlODVkMTBjIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQABAMDAwMDBAMDBAYEAwQGBgUEBAUGBwYGBgYGBwoHCAgICAcKCgsMDAwLCgwMDAwMDBERERERExMTExMTExMTEwEEBAQIBwgOCgoOFA4ODhQUExMTExQTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMTExMT/8AAEQgA+gEsAwERAAIRAQMRAf/EANkAAQAABwEBAAAAAAAAAAAAAAABAgMEBgcIBQkBAQACAwEBAQAAAAAAAAAAAAABAgMFBgQHCBAAAQMDAwEEBAYMBgoPCQEAAQIDBAAFBhESByExIhMIQVFhFHGBkTJCFaGxUmJyIzPTlBZWGMGS0tRVF9GCorJDJLSFpTdTY3OzNFR0NXWVtXaWVzjwk6NEhOQlZ1gJEQACAQIDBAYHBgQDCAMBAAAAAQIRAxIEBSExQVFhcZHREwaBoSIyUhQW8OFCotJTscFiFXKyI/GSwjNDJDRUgpM1Y//aAAwDAQACEQMRAD8A4JrZkCgFAKAUAoBQCgFAKAnV+TR8Kv4KggkqSSdz6H4IqEQiSpJFAKAUAoBQCgFAKAUAoBQCgFARV84/DREIhQkUAoBQCgFAKAUAoBQCgFAKAUAoBQCgFAKAUBOr8kj4VfwVHEgkqSSd36H4IqEQiSpJFAKAUAoBQCgFAKAUAoBQCgFARX89XwmiIRChIoBQCgFAKAUAoBQCgFAXdptdwvl0g2W0x1y7r';
//        $a = file_put_contents('test.png', base64_decode($url[1]));//返回的是字节数

        $url=explode(",",$base64);
        file_put_contents("test1.jpg",base64_decode($url[1]));
//        $data['pattern']=C("pattern"); //获取验证码识别默认规则
        $this->show("test");die;
        $this->assign($data);
        $this->display();
    }

    public function subscribe () {
        $wxResponse = D("WxResponse","Logic");
        $res = $wxResponse->chatBack();
        $wxResponse->back($res['openid'],$res['mediaid'],"关注事件消息推送！   ".$res['openid']);
//        $wxResponse->kefu_text($res['openid'],"关注事件客服消息");
    }

    public function luck() {
        echo U("Home/Index/luck","",false,true);
        die("test");
        $code = I("get.code");
        $res = D("WxScope","Logic")->getUnionid($code);
        if( ! empty($res['unionid']) && ! empty($res['openid']) ) {
            session("openid",$res['openid']);
            session("unionid",$res['unionid']);
            $res = D("Code")->getOneInfo(array(
                'unionid'=>$res['unionid'],
                'version'=>getCurrentVersion()
            ));
            $data['initImg']= ($res['shareimg']=="null" || empty($res['shareimg'])) ? "./Public/img/upload.png" : $res['shareimg'];
            $data['upImg'] = !empty($res['luckcode']) ? 1 : 2;
        }
        else if( session("?openid")  && session("?unionid") ) {
            $res = D("Code")->getOneInfo(array(
                'unionid'=>session("unionid"),
                'version'=>getCurrentVersion()
            ));
            $data['initImg']= ($res['shareimg']=="null" || empty($res['shareimg'])) ? "./Public/img/upload.png" : $res['shareimg'];
            $data['upImg'] = !empty($res['luckcode']) ? 1 : 2;
        }
        else {
            $data['initImg']="./Public/img/upload.jpg";
            $data['upImg'] = 2;
        }
        $data['ajaxImgData']=U("Home/Index/ajaxImgData","",false);
//        die(U("Home/Index/ajaxImgData","",false,true));
//        $data['ajaxImgData'] = U("Home/Index/ajaxImgData","",false);
        $this->assign($data);
        $this->display();
    }

    public function detail() {
        $code = I("get.code");
        $version = I("get.state");
        if(empty($version)) {
            $data['isSub']=2;
        }
        else {
            $res = D("WxScope","Logic")->getUnionid($code);
            session("openid",$res['openid']);
            session("unionid",$res['unionid']);
            $code = D("Code");
            if( ! empty($res['unionid']) && ! empty($res['openid']) ) {
                if($code->isExist(array(
                    'unionid'=>$res['unionid'],
                    'version'=>$version
                ))) {
                    $data['isSub']=1;
                }
                else {
                    $data['isSub']=2;
                }
            }
            else if( session("?openid")  && session("?unionid") ) {
                if($code->isExist(array(
                    'unionid'=>session("unionid"),
                    'version'=>$version
                ))) {
                    $data['isSub']=1;
                }
                else {
                    $data['isSub']=2;
                }
            }
            else {
                $data['isSub']=2;
            }
        }
        $data['ajaxSub']=U("Home/Index/ajaxSub","",false);
        $this->assign($data);
        $this->display();
    }

    public function ajaxSub() {
        if(isset($_POST['phonenum'])) {
            $phoneNum = I("post.phonenum");
            if(session("?unionid")) {
                if(D("User")->update(session("unionid"),array('phonenum'=>$phoneNum))) {
                    $this->ajaxReturn(array("status"=>1,'info'=>"ok"));
                }
                else {
                    $this->ajaxReturn(array("status"=>-3,'info'=>"内部错误，请重试2"));
                }
            }
            else {
                $this->ajaxReturn(array("status"=>-2,'info'=>"请在微信端重新打开链接"));
            }
        }
        else {
            $this->ajaxReturn(array("status"=>-1,'info'=>"未能接收到数据"));
        }

    }

    /*
     * 1、先验证是否有图片
     * 2、验证是否已经授权登录
     * 3、验证是否已经在公众号上抽奖
     * */
    public function ajaxImgData() {
        if(isset($_POST['imgData'])) {
            if(session("?unionid")) {
                $version = getCurrentVersion();
                $unionid = session("unionid");
                $code = D("Code");
                $imgData = I("post.imgData");
                $imgFile = C("imgFile");
                $imgSourceArr = explode(",",$imgData);
                $fileName = $imgFile."/".$unionid.rand(1000,10000).".jpg";
                file_put_contents($fileName,base64_decode($imgSourceArr[1]));
                if($code->isExist(array(
                    'unionid'=>$unionid,
                    'version'=>$version
                ))) {
                    if($code->update(array('unionid'=>"$unionid",'version'=>"$version"),array('shareimg'=>"$fileName"))) {
                        $this->ajaxReturn(array("status"=>1,'info'=>"ok",'testInfo'=>$imgData,'url'=>"http://wx.aufe.vip/luck/".$fileName));
                    }
                    else {
                        $this->ajaxReturn(array("status"=>-4,'info'=>"内部错误，请重试1"));
                    }
                }
                else {
                    $addArr = array(
                        'unionid'=>"$unionid",
                        'luckcode'=>random(),
                        'shareimg'=>$fileName,
//                        'time'=>time(),
                        'version'=>$version,
                    );
                    if($code->addNew($addArr)) {
                        $this->ajaxReturn(array("status"=>1,'info'=>"ok",'testInfo'=>$imgData,'url'=>"http://wx.aufe.vip/luck/".$fileName));
                    }
                    else {
                        $this->ajaxReturn(array("status"=>-5,'info'=>"内部错误，请重试2"));
                    }
                }
            }
            else {
                $this->ajaxReturn(array("status"=>-2,'info'=>"请在微信端重新打开链接"));
            }
        }
        else {
            $this->ajaxReturn(array("status"=>-1,'info'=>"未能接收到数据"));
        }
    }

    public function initBookCode () {
//        $openid="o16hwwc9f968Bk-74Q69la9eAJwA";
//        D("WxResponse","Logic")->kefuMsg_text($openid,"关注客服消息测试");
//        $res = array();
//        for($i=0;$i<100;++$i) {
//            $res[]=random(15);
//        }
//        dump(json_encode($res));
//        file_put_contents("./Public/resource/bookcode.json",json_encode($res));
    }

    public function insertData() {
        $version = getCurrentVersion();
        $shareImg = "./Public/shareImg/testopenid7580.jpg";
        $code = D("Code");
        $user = D("User");
        for($i=0;$i<1284;++$i) {
            $data=array(
                'unionid'=>random(28),
                'luckcode'=>random(),
                'shareimg'=>$shareImg,
                'time'=>time()+$i,
                'version'=>$version
            );
            echo $code->addNew($data)."\r\n";
            $userData = array(
                'unionid'=>$data['unionid'],
                'openid'=>$data['unionid'],
                'time'=>time(),
                'nickname'=>random(15),
                'phonename'=>random(15),
            );
            echo $user->addNew($userData)."\r\n";
        }
    }


}
