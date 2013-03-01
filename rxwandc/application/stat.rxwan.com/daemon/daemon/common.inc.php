<?php
include(PATH_ROOT.'lib/functions.inc.php');
include(PATH_ROOT.'lib/httpsqs.php');

//数据库配置
$cfg_db = array(
    'stat2'=>array(
        'driver'=>'mysql',
        'host'=>'stat2.db.caiyun.com',
        'port'=>'3307',
        'username'=>'root',
        'password'=>'10dbce2394324d',
        'dbname'=>'install2',
        'charset'=>'utf8'
    ),
    'alliance'=>array(
        'driver'=>'mysql',
        'host'=>'alliance.db.caiyun.com',
        'port'=>'3302',
        'username'=>'root',
        'password'=>'10dbce2394324c',
        'dbname'=>'alliance',
        'charset'=>'utf8'
    )
);

//HTTPSQS配置
$cfg_httpsqs = array(
	'host' => 'queue.memdb.caiyun.com',
	'port' => 20001,
	'charset' => 'UTF-8',
	'active' => 'active',
	'active2'=>'active2',
	'install'=>'install',
	'install2'=>'install2',
	'uninstall'=>'uninstall',
	'uninstall2'=>'uninstall2',
	"server2"=>"server2",
	"sem"=>"sem",
	"tb"=>"tb"
);

//缓存配置
$cfg_cache = array(
	'default'=>array(
		'driver'=>'memcache',
		'host'=>'large.memdb.caiyun.com',
		'port'=>11311,
		'prefix'=>'',
        'expire'=>0
	),
	'large'=>array(
		'driver'=>'memcache',
		'host'=>'large.memdb.caiyun.com',
		'port'=>11311,
		'prefix'=>'',
        'expire'=>0
	)
);

//定义联盟表以及数据库
define('TABLE_ALLIANCE_MEMBER', 'member');
define('DB_ALLIANCE','alliance');
define('DB_DOWNLOAD','download');//定义download数据库

//配置日志文件
define('LOG_ACTIVE','active.log');
define('LOG_INSTALL','install.log');
define('LOG_UNINSTALL','uninstall.log');

define('TIME',time ());

foreach($cfg_db as $k=>$cfg_db_one){
    $cfg_db[$k]['link'] = dblink($k);
}