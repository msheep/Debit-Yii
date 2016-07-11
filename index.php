<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
// deploy config file with diff environment by HTTP_HOST
$config=dirname(__FILE__).'/protected/config/'.$_SERVER['HTTP_HOST'].'.php'; 
if(!is_file($config)) die('Sorry For Maintenance');
if(stripos($_SERVER['HTTP_HOST'],"thor.com")!==false)  { //本地测试环境
	define('LOCALHOST',true);
    define('DOMAIN', 'www.thor.com');
    // @var 去买呀boss登录配置（本地测试环境）
	define( "WB_AKEY" , '1f53f4c9e37f09e7dd1a737ab10c30b105237cf44' );//服务端注册的id
	define( "WB_SKEY" , '6218d4b39d87dea94c4f78d30db2a8e0' );//服务端注册的pass
	define( "WB_CALLBACK_URL" , 'http://boss.thor.com/qmy/callback' );//服务端注册的redirect_uri  
}else if(stripos($_SERVER['HTTP_HOST'],"new.")!==false)  { //本地测试环境
    define('DOMAIN', 'new.www.thortest.com');
    // @var 去买呀boss登录配置（本地测试环境）
	define( "WB_AKEY" , '5cee46e5aea528229613865e805140760525fe63f' );//服务端注册的id
	define( "WB_SKEY" , '6097284be0e89c12da12081e09fe5ee6' );//服务端注册的pass
	define( "WB_CALLBACK_URL" , 'http://new.boss.thortest.com/qmy/callback' );//服务端注册的redirect_uri  
}else if($_SERVER['HTTP_HOST'] =='www.thortest.com') { // 主站 生成环境
    define('PRODUCTION',true);
    define('DOMAIN', 'www.thortest.com');
}else{
    define('DOMAIN', 'www.thortest.com'); //默认主域名, 用于 子域名
}

// remove the following lines when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',2);

require_once($yii);
Yii::createWebApplication($config)->run();
