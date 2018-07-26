<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace database;

//数据库抽象类,所有驱动必须实现此类
abstract class DatabaseAb
{
    //sql语句
    protected $sql;
    protected $where;
    protected $order;
    protected $limit;
    protected $table;
    protected $field;
    //所要操作的数据表
    public function table($table){
        $this->table=$table;
        return $this;
    }
    //where条件查询语句
    public function where($sql){
        if(!is_null($this->where)){
            $this->where.=" AND {$sql}";
        }else{
            $this->where=" WHERE {$sql}";
        }
        return $this;
    }
    //order排序语句
    public function order($sql){
        $this->order=" ORDER BY {$sql}";
        return $this;
    }
    //所要获取的字段以逗号分隔
    public function field($sql){
        $this->field=$sql;
        return $this;
    }
    //limit数据获取限制语句
    public function limit($number){
        $this->limit=" LIMIT {$number}";
        return $this;
    }
    //原生sql查询
    public function query($sql){
        return $this->_db->query($sql);
    }
    //获取单条数据
    public function get(){
        $res=$this->select();
        if(is_array($res)){
            return $res[0];
        }
    }
    //获取多条数据
    public function select(){
        if(is_null($this->table)){
            return false;
        }
        if(is_null($this->field)){
            $sql="select * from {$this->table}";
        }else{
            $sql="select {$this->field} from {$this->table}";
        }

        if(!is_null($this->where)){
            $sql.=$this->where;
        }
        if(!is_null($this->order)){
            $sql.=$this->order;
        }
        if(!is_null($this->limit)){
            $sql.=$this->limit;
        }
        //输出查询结果
        return $this->_db->query($sql);
    }

    /**
     * @param array $datas 插入数据/数组格式
     */
    public function insert($datas=array()){
        //判断是否设置表名
        if(is_null($this->table)){
            throw new \Exception('table name not set');
        }

        if(is_array($datas) && !empty($datas)){
            //用户存储数组健与值
            $key=null;
            $val=null;
            //获取数组中的健与值
            foreach ($datas as $k=>$v){
                if(is_null($key)){
                    $key=$k;
                }else{
                    $key.=','.$k;
                }

                if(is_null($val)){
                    $val="'{$v}'";
                }else{
                    $val.=",'{$v}'";
                }
            }
            $sql="INSERT INTO {$this->table} ($key) VALUES ($val)";
        }else{
            throw new \Exception('format error params must be array ');
        }
        return $this->_db->query($sql);
    }

    /**
     * @param $val 索引值
     * @param string $field 默认以id字段删除
     */
    public function delete($val,$field='id'){
        $this->isTable();
        //删除多个
        if(is_string($val) || is_integer($val)){
            $sql="DELETE FROM {$this->table} where {$field} in($val)";
            return $this->_db->query($sql);
        }
    }

    /**
     * 更新数据
     * 可接受直接在$datas中传递id,也支持通过where方法传递更新条件
     * @param array $datas 数组格式数据
     * @param string $index 更新条件索引 如index=1,或者field=t
     * @throws \Exception
     */
    public function update($datas=array(),$index=array()){
        if(empty($datas)){
            return;
        }
        $this->isTable();
        if(isset($datas['id']) || !is_null($this->where)){
            if(is_array($datas)){
                $up=null;
                foreach ($datas as $k=>$v){
                    if(is_null($up)){
                        $up="{$k}='{$v}'";
                    }else{
                        $up.=",{$k}='{$v}'";
                    }
                }
                if($index == 'id'){
                    //组合更新sql语句
                    $id=isset($datas['id'])?"id=".$datas['id']:"{$this->where}";
                }else{
                    $id=$index;
                }
                    $sql="UPDATE {$this->table} SET {$up} WHERE {$id}";
                }
        }else{
            //如果参数中没有id并且where也没有增加条件则抛出异常
            throw new \Exception('Index value not exists');
        }
        return $this->_db->query($sql);
    }
    //检测是否指定表名
    protected function isTable(){
        if(is_null($this->table)){
            throw new \Exception('empty of table name');
        }
    }
}