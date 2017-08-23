<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL'             =>  '3',
    'DB_PARAMS'             =>  array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),//设置数据库字段为大小写敏感

    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '121.42.57.23',   // 服务器地址
    'DB_NAME'               =>  'luck',          // 数据库名
    'DB_USER'               =>  'woai662',      // 用户名
    'DB_PWD'                =>  'weiwei66291',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'luck_',    // 数据库表前缀

    'imgFile'               =>  './Public/shareImg'


);