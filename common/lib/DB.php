<?php
namespace common\lib;
use Medoo\Medoo;

/**
 * Class db;
 * @package common\lib
 * 如果mysql采用长连接，长时间没操作mysql服务器会自动断开连接
 * 此后再查询会发生 mysql server gone away
 * 为了方便，采用短连接的方式，每次使用完后需要关闭连接
 * 这样会带来额外每次连接mysql server的开销
 */
class DB {

    public $medoo;

    public static $tableName ;

    public function __construct(Medoo $medoo)
    {
        $this->medoo = $medoo;
    }
}