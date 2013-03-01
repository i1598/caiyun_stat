<?php
include PATH_ROOT.'lib/functions.inc.php';
ini_set ('memory_limit', '512M');

$cfg_db = array(
    'advert_show'=>array(
        'driver'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'username'=>'root',
        'password'=>'soft798.com',
        'dbname'=>'advert_show',
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
    )
);


define('IS_DEBUG', true);
define('TABLE_ADVERTISE', 'ad.advertise');
define('TIME',time());
foreach($cfg_db as $k=>$cfg_db_one){
    $cfg_db[$k]['link'] = dblink($k);
}
