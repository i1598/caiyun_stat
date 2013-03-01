<?php
include PATH_ROOT.'lib/functions.inc.php';
ini_set ('memory_limit', '512M');

$cfg_db = array(
    'stat'=>array(
        'driver'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'username'=>'root',
        'password'=>'soft798.com',
        'dbname'=>'install',
        'charset'=>'utf8'
    ),
    
    'reports'=>array(
        'driver'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'username'=>'root',
        'password'=>'soft798.com',
        'dbname'=>'reports',
        'charset'=>'utf8'
    ),
    
    'alliance'=>array(
        'driver'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'username'=>'root',
        'password'=>'soft798.com',
        'dbname'=>'alliance',
        'charset'=>'utf8'
    )
);


define('IS_DEBUG', true);
define('TABLE_SOFTWARE', '`soft`.`soft_name`');
define('TABLE_REPORT_DAY', 'reports.report_day');
define('TABLE_ALLIANCE_MEMBER','`alliance`.`member`');
//$time = $argc>1 ? (strtotime($argv[1])+$argv[2]):time();
//echo "Total Argcount: $argc.\n";
define('TIME',time());

foreach($cfg_db as $k=>$cfg_db_one){
    $cfg_db[$k]['link'] = dblink($k);
}
