#!/opt/modules/php-fcgi/bin/php -f 
<?php
//定义加载路径

define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'daemon2/common.inc.php');
$sleep_time = 1;

//实例化类
$httpsqs = new httpsqs ();

//守护进程
while ( true ) {
	
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['sem'] );
	$pos = $result ["pos"]; //当前队列消息的读取位置点  
	$json_data = $result ["data"]; //当前队列消息的内容  
	if ($json_data != "HTTPSQS_GET_END" && $json_data != "HTTPSQS_ERROR") {
		//将json格式转换为数组
		$data = json_decode ( $json_data, true );
		if (! empty ( $data )) {
					//进行分表操作
					$postfix = $data['ip']%256;
					$table_name = "download_".$postfix; 
					$sql = get_insert_sql ( $table_name, $data, DB_DOWNLOAD, 'stat2' );
					query ( $sql, 'stat2' );
		}
	} else {
		sleep ( $sleep_time ); //暂停1秒钟后，再次循环  
	}
}
