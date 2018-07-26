<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */
namespace database;
class Db extends DatabaseAb
{
    //数据库实例,与父类共享使用此变量
    protected $_db;
    private static $params=array(
        'driver'=>'mysqli',
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'dbname'=>'blog',
        'charset'=>'utf8'
    );
    //添加一个数据库连接(将数据库参数设置到当前类中)
    public function addConnect($params=array()){
        foreach ($params as $k=>$v){
            self::$params[$k]=$v;
        }
        $this->driver();
    }
    //通过驱动来加载相应类
    private function driver(){
        if(is_null(self::$params['driver'])){
            return false;
        }
        //获取当前数据库驱动
        $driver='database\driver\\'.self::$params['driver'];
        $this->_db=$driver::getInstance(self::$params['host'],self::$params['username'],self::$params['password'],self::$params['dbname']);
    }
}