<?php
/* $dbms='mysql';     //数据库类型 Oracle 用ODI,对于开发者来说，使用不同的数据库，只要改这个，不用记住那么多的函数了
$host='127.0.0.1'; //数据库主机名
$dbName='helios';    //使用的数据库
$user='root';      //数据库连接用户名
$pass='';          //对应的密码
$dsn="$dbms:host=$host;dbname=$dbName"; */

$config=dirname(__FILE__).'/../protected/config/'.$_SERVER['HTTP_HOST'].'.php';
$config = include $config;
try {
    //$dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象，就是创建了数据库连接对象$dbh
    $dbh = new PDO($config['components']['db']['connectionString'], $config['components']['db']['username'], $config['components']['db']['password']);
    echo "OK!";
    /*你还可以进行一次搜索操作
    foreach ($dbh->query('SELECT * from FOO') as $row) {
        print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
    }
    */
    $dbh = null;
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}
//默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
//$db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));
?>