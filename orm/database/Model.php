<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace database;
class Model extends DatabaseAb
{
    //子类模型指定相应表名
    protected $table=null;

    //将数据库映射到数组中
    private static $mapper=[];

    //用于save方法保存时
    private static $setMapper=[];
    protected $_db;
    public function __construct()
    {
        $this->_db=new Db();
        $this->_db->addConnect();
    }

    /**
     * 查询数据并将结果映射到数组中
     * @param $id
     * @return array|mixed
     */
    public function find($id){
        $res=$this->_db->table($this->table)->where("id={$id}")->get();
        self::$mapper=$res;
        return self::$mapper;
    }

    //将修改后的属性进行入库
    public function save(){
        $id=null;
        if(isset(self::$setMapper['id'])){
            $id=self::$setMapper['id'];
            unset(self::$setMapper['id']);
        }
        $q=null;
        foreach (self::$setMapper as $k=>$v){
            if(is_null($q)){
                $q="{$k}='{$v}'";
            }else{
                $q.=",{$k}='{$v}'";
            }
        }
        if(is_null($this->where)){
            $sql="UPDATE {$this->table} SET {$q} WHERE id={$id}";
        }else{
            $sql="UPDATE {$this->table} SET {$q}{$this->where}";
        }
        return $this->_db->query($sql);
    }

    public function hasOne($table=null,$foreign=null,$localkey=null){
        $this->isTable();
        $sql="SELECT * FROM {$this->table} left join {$table} on {$this->table}.{$localkey} = {$table}.{$foreign}";
        return $this->query($sql);
    }
    /**
     * 在映射数组中查询数据
     * @param $name
     * @return bool|mixed
     */
    public function __get($name)
    {
        if(isset(self::$setMapper[$name])){
            return self::$setMapper[$name];
        }
        if(isset(self::$mapper[$name])){
            return self::$mapper[$name];
        }
        return false;
    }

    /**
     * 设置要保存的数据
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if(isset(self::$mapper[$name])){
            self::$setMapper['id']=self::$mapper['id'];
            self::$setMapper[$name]=$value;
        }
    }
}