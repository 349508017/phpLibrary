<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace database\driver;


class Mysqli implements Database
{
    //单例对象
    private static $_instance;
    //保存数据库实例
    private $db;
    //单例模式
    private function __construct($host,$username,$passwd,$dbname,$charset='utf8')
    {
        //连接数据库
        $this->connect($host,$username,$passwd,$dbname,$charset='utf8');
    }
    private function __clone()
    {

    }
    public static function getInstance($host,$username,$passwd,$dbname,$charset='utf8'){
        if(is_null(self::$_instance)){
            self::$_instance=new self($host,$username,$passwd,$dbname,$charset='utf8');
        }
        return self::$_instance;
    }
    //与数据库建立连接
    public function connect($host,$username,$passwd,$dbname,$charset='utf8')
    {        //连接数据库
        $this->db=mysqli_connect($host,$username,$passwd,$dbname);
        $this->query("set names {$charset}");
    }
    //数据库sql语句执行方法
    public function query($sql)
    {
        try{
            $res=mysqli_query($this->db,$sql);
            if($res === false){
                throw new \Exception(mysqli_error($this->db));
            }else{
                $mod=strtolower(substr($sql,0,6));
                //如果是查询则返回查询到的数据
                if($mod == 'select'){
                    $res=mysqli_fetch_all($res,MYSQLI_ASSOC);
                }
                //如果是新增则返回新增后的id
                if($mod == 'insert'){
                    $res=mysqli_insert_id($this->db);
                }
                //更新与删除,则返受影响的行数
                if($mod == 'update' || $mod == 'delete'){
                    $res=mysqli_affected_rows($this->db);
                }
            }
        }catch(\Exception $e){
            throw new \Exception($e);
        }
        return $res;
    }
    public function close()
    {
        mysqli_close($this->db);
    }
}