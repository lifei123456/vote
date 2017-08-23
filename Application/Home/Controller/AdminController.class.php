<?php
/**
 * Created by PhpStorm.
 * User: zhengwei
 * Date: 2017/4/10
 * Time: 上午10:48
 */

namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {

    public function index() {
        $res = json_decode(file_get_contents("./Public/resource/conf.json"),true);
        $data['tplText']=$res['returnText'];
        $data['ajaxData']=U("Home/Admin/ajaxData","",false);
        $data['ajaxOne']=U("Home/Admin/getOneLuck","",false);
        $data['setTpl']=U("Home/Admin/setTpl","",false);
        $data['init']=U("Home/Admin/init","",false);
        $data['send']=U("Home/Admin/send","",false);
        $this->assign($data);
        $this->display();
    }

    /*
     * 抽奖逻辑：
     * 获取POST总数量count
     * 通过数据库获取可能抽奖的总数据条数verCount
     * 出于控制内存的目的，一个数组放1000($interval)条数据，一共需要循环verCount/1000-1（向下取整,$time-1）次，
     * 每次抽奖人数为$perCount = count*(1000/verCount)次，最后一次将于for循环外执行
     *
     * */
    public function ajaxData() {
//        $interval = 80;
        if(isset($_POST['count'])) {
//            $result = array();
//            $count=I("post.count");
//            $version=getCurrentVersion();
//            $code = D("Code");
//            $verCount=$code->getAllCount($version);
//            $startId=$code->getId($version);
//            if($verCount > 2*$interval) {
//                $time = (int)($verCount/$interval);
//                $perCount = (int)(($count*$interval)/$verCount); //本次应抽数量
////            $thisData=array(); //本次抽奖总用户
//                for($i=1;$i<$time;++$i) {
//                    $thisData = $code->getLimitCount($startId,$version,$interval);
//                    $this->getThisResultArr($interval,$perCount,$thisData,$result);
//                    $startId+=$interval;
//                }
//                //执行最后一次抽奖
//                $test = $perCount;
//
//
//                $perCount=$count-$perCount*($time-1);
//                $thisData = $code->getLimitCount($startId,$version,$verCount-$interval*($time-1));
//                $this->getThisResultArr($verCount-$interval*($time-1),$perCount,$thisData,$result);
//            }
//            else {
//                $time=1;
//                $test=$count;
//                $perCount=$count;
//                $thisData = $code->getLimitCount($startId,$version,$verCount);
//                $this->getThisResultArr($verCount,$perCount,$thisData,$result);
//            }

            $result=D("Code")->getCountData(getCurrentVersion(),I("post.count"));
            $this->ajaxReturn(array(
                'status'=>1,
                'info'=>"ok",
                'data'=>$result,
                'count'=>count($result)
//                'verCount'=>$verCount,
//                'time'=>$time,
//                'perCount'=>$test,
//                'lastPerCount'=>$perCount,
            ));
        }
        else {
            $this->ajaxReturn(array(
                'status'=>-1,
                'info'=>"缺少参数"
            ));
        }
    }

    public function getOneLuck() {
        $this->ajaxReturn(array(
            'status'=>1,
            'info'=>"ok",
            'data'=>D("Code")->getOne(getCurrentVersion()),
        ));
    }

    //初始化抽奖
    public function init() {
        $res = json_decode(file_get_contents("./Public/resource/conf.json"),true);
        $res['version']+=1;
        file_put_contents("./Public/resource/conf.json",json_encode($res,JSON_UNESCAPED_UNICODE));
        $this->ajaxReturn(array(
            'status'=>1,
            'info'=>"ok"
        ));
    }

    //设置模板
    public function setTpl() {
        if(isset($_POST['tplText'])) {
            $res = json_decode(file_get_contents("./Public/resource/conf.json"),true);
            $res['returnText']=I("post.tplText");
            file_put_contents("./Public/resource/conf.json",json_encode($res,JSON_UNESCAPED_UNICODE));
            $this->ajaxReturn(array(
                'status'=>1,
                'info'=>"ok"
            ));
        }
        else {
            $this->ajaxReturn(array(
                'status'=>-1,
                'info'=>"lost params"
            ));
        }
    }

    /*
     * 向已经中奖的人员再次发送客服消息
     * */
    public function sendMsgAgain() {
        $text="恭喜您获得我们的《Python机器学习》书籍，\n我们后续计划邀请书籍译者老师与大家进行交流，\n如果您感兴趣，欢迎添加小象运营（shabao2333）微信好友并回复接头暗号“机器学习”，我们会将您拉入微信群中，等待老师的到来";
        $res=D("Code")->getLuckInfo();
//        dump($res);die;
        $count=0;//发送成功的人数
        $wxResponse=D("WxResponse","Logic");
        foreach ($res as $item) {
            if($item['id'] > 3) {
                //测试数据id=1、2、3，以后用这个id可以删掉
                $res = json_decode($wxResponse->kefuMsg_text($item['openid'],$text),true);
                if($res['errcode'] == "40001") {
                    $wxResponse->updateAccessToken();
                    $res = json_decode($wxResponse->kefuMsg_text($item[0],sprintf($text,$item[1])),true);
                }
                if($res['errcode'] == "0") {
                    $count++;
                }
            }
        }
        echo "成功发送信息".$count."条！";
    }

    public function send() {
        $openid = I("post.openid");
        \Think\Log::write(json_encode($openid),'IMPORTANT');
        $text="恭喜您抽奖成功，您的兑换码为%s";
        $wxResponse=D("WxResponse","Logic");
        $version = getCurrentVersion();
//        var_dump($openid);die;
//        $testOpenid="o16hwwb9HjRJ9uxDHWqd4FoHdeFI";
//        dump($wxResponse->kefuMsg($testOpenid));
        $code = D("Code");
        $boolVar = 0;
        $secondArr = array();
//        $wxResponse->init();
        foreach ($openid as $item) {
            $res = json_decode($wxResponse->kefuMsg_text($item[0],sprintf($text,$item[1])),true);
            if($res['errcode'] == "40001") {
                $wxResponse->updateAccessToken();
                $res = json_decode($wxResponse->kefuMsg_text($item[0],sprintf($text,$item[1])),true);
            }
            if($res['errcode'] != "0") {
                $secondArr[]=$item;
            }
            else {
                $code->update(array('unionid'=>$item[2],'version'=>$version) , array(
                    'bookcode'=>$item[1],
                    'isluck'=>1
                ));
            }
        }
        if( count($secondArr) != 0) {
            foreach ($secondArr as $item) {
                $res = json_decode($wxResponse->kefuMsg_text($item[0],sprintf($text,$item[1])),true);
                if($res['errcode'] != "0") {
                    $boolVar++;
                }
                else {
                    $code->update(array('unionid'=>$item[2],'version'=>$version) , array(
                        'bookcode'=>$item[1],
                        'isluck'=>1
                    ));
                }
            }
        }
        if($boolVar == 0) {
            $this->ajaxReturn(array(
                'status'=>1,
                'info'=>"ok",
            ));
        }
        else {
            $this->ajaxReturn(array(
                'status'=>-1,
                'info'=>"ok",
                "count"=>$boolVar,
            ));
        }


//        dump($openid);
//        dump($openid[1]);
//        dump($openidArr);

        die();
        //
        $this->ajaxReturn(array(
            'status'=>1,
            'info'=>"ok",
            'data'=>"ok".var_dump($openidArr),
        ));
    }

    //获得当前抽奖的结果集
    private function getThisResultArr($length,$count,&$thisData,&$result) {
        $resIndexArr = luck(0,$length-1,$count);
        foreach ($resIndexArr as $item) {
            $result[] = $thisData[$item];
        }
    }

//    //获得抽奖最终的result数据集
//    private function getResult(&$user,&$result) {
//
//    }



}


