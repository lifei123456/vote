<?php
/**
 * Created by PhpStorm.
 * User: zhengwei
 * Date: 2017/4/9
 * Time: 下午9:27
 */


namespace Home\Model;
use Think\Model;

class CodeModel extends Model {
    /*
     * 允许传入数组和非数组以验证是否存在，存在返回true
     * */
    public function isExist($arr) {
        if($this->where($arr)->count() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    //新增数据
    public function addNew($arr) {
        if($this->data($arr)->add()){
            return true;
        }
        else{
            return false;
        }
    }

    /*
     * 根据 unionid和version 更新函数
     * 参数1、2：需要更改的用户where条件
     * 参数3：待更新的关联数组
     * */
    public function update($selectArr,$upArr) {
        if($this->where($selectArr)->data($upArr)->save() !== false) {
            return true;
        }
        else{
            return false;
        }
    }

    /*
     * 获取用户基本信息
     * */
    public function getOneInfo($arr){
        return $this->where($arr)->find();
    }

    /*
     * 获得某个version下符合抽奖条件的总人数
     * 根据逻辑：判定条件为time!=0 && shareimg!=null && version='$version'
     * */
    public function getAllCount($version) {
        return $this->where("`time`>0 and `shareimg`!='null' and `version`='$version'")->count();
    }
    //select a.unionid,a.shareimg,b.nickname as name from luck_code as a,luck_user as b WHERE a.time>0 and a.shareimg!='null' and a.version='10' and a.unionid=b.unionid order by rand() limit 100

    /*
     * 随机抓取一个数据
     * */
    public function getOne($version) {
//        return $this->query("select `unionid`,`luckcode`,`shareimg`,`time` as name from luck_code WHERE `time`>0 and `shareimg`!='null' and `version`='$version' order by rand() limit 1");
        return $this->field("shareimg,openid,luck_user.unionid,nickname as name,luckcode")->join("__USER__ on __CODE__.unionid = __USER__.unionid and __CODE__.version='$version' and __CODE__.time>0 and __CODE__.shareimg!='null'")->order('rand()')->limit(1)->select();
    }

    /*
     * 按照要求抓取指定数目的数据
     * select `unionid`,`luckcode`,`shareimg`,`time` from luck_code WHERE `time`>0 and `shareimg`!='null' and `version`='10' order by rand() limit 200
     * */
    public function getCountData($version,$count) {
        return $this->field("shareimg,openid,luck_user.unionid,nickname as name,luckcode")->join("__USER__ on __CODE__.unionid = __USER__.unionid and __CODE__.version='$version' and __CODE__.time>0 and __CODE__.shareimg!='null'")->order('rand()')->limit($count)->select();
//        return $this->query("select `unionid`,`luckcode`,`shareimg`,`time` as name from luck_code WHERE `time`>0 and `shareimg`!='null' and `version`='$version' order by rand() limit $count");
    }

    /*
     * 获得某个version下的第一个人的id
     * */
    public function getId($version) {
        $res = $this->where("`version`='$version'")->order("id asc")->find();
        return $res['id'];
    }

    /*
     * 根据初始 id 和 count 抓取详细数据
     * */
    public function getLimitCount($id,$version,$count) {
        return $this->field("unionid,luckcode,shareimg,time")->where("`time`>0 and `shareimg`!='null' and `id` >= '$id' and `version`='$version'")->limit($count)->select();
    }

    /*
     * 获得中奖信息，因为是后台，没考虑sql消耗，就酱紫吧
     * */
    public function getLuckInfo() {
        return $this->join("__USER__ on __USER__.unionid=__CODE__.unionid and __CODE__.isluck=1")->select();
        return $this->where("`isluck`=1")->select();
    }




}

