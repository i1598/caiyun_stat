#!/opt/modules/php-fcgi/bin/php -f 
<?php
//定义加载路径
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'daemon2/common.inc.php');
$sleep_time = 30;

//实例化类
$httpsqs = new httpsqs ();

//守护进程
while ( true ) {
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['server2'] );
	$pos = $result ["pos"]; //当前队列消息的读取位置点  
	$json_data = $result ["data"]; //当前队列消息的内容

	if ($json_data != "HTTPSQS_GET_END" && $json_data != "HTTPSQS_ERROR"){

		//将json格式转换为数组
		$data = json_decode ( $json_data, true );
		if(empty($data)){
			sleep( $sleep_time );
		}
		$data['coop'] = trim(sbc2abc($data ['coop']));

		$db_install = database ( 'install2', $data ['uuid'] );
		$table_install = table ( 'install', $data ['uuid'] );
		$tableServer = table ( 'server', $data ['uuid'] );

		$sql = "SELECT * FROM `" . $db_install . "`.`" . $table_install . "` WHERE `uuid`='" . $data ['uuid'] . "'";
		$data_install = getRow ( $sql, 'stat2' );

		if(empty($data_install))
			$data["is_miss"] = 1;

		$sql = get_insert_sql ( $tableServer, $data, "server2", 'stat2' );
		query ( $sql, 'stat2' );
		
	}else{
		sleep ( $sleep_time );
	}
	
}
