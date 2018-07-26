<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

/*数据库适配器接口*/
namespace database\driver;
interface Database
{
    //单例模式
    public static function getInstance($host,$username,$passwd,$dbname,$charset);
    public function connect($host,$username,$passwd,$dbname,$charset);
    public function query($sql);
    public function close();
}