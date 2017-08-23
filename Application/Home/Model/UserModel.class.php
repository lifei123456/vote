<?php
/**
 * Created by PhpStorm.
 * User: zhengwei
 * Date: 2017/4/9
 * Time: 下午8:47
 */

namespace Home\Model;
use Think\Model;

class UserModel extends Model {
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
     * 根据 unionid 更新函数
     * 参数1：需要更改的用户 unionid
     * 参数2：待更新的关联数组
     * */
    public function update($unionid,$upArr) {
        if($this->where("`unionid`='$unionid'")->data($upArr)->save() !== false){
            return true;
        }
        else{
            return false;
        }
    }



}

