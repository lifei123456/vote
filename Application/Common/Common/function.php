<?php
/**
 * Created by PhpStorm.
 * User: zhengwei
 * Date: 2017/4/9
 * Time: 下午2:49
 */



/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function random($length=7, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/*
//range 是将1000到9999 列成一个数组
$numbers = range (1000,9999);
//shuffle 将数组顺序随即打乱
shuffle ($numbers);
//array_slice 取该数组中的某一段
$result = array_slice($numbers,0,3);
print_r($result);
 * */
function luck($start,$end,$count) {
    $numbers = range ($start,$end);
    shuffle ($numbers);
    return array_slice($numbers,0,$count);
}

/*
 * 获得当前抽奖编号
 * */
function getCurrentVersion() {
    $res = json_decode(file_get_contents("./Public/resource/conf.json"),true);
    return $res['version'];
}


