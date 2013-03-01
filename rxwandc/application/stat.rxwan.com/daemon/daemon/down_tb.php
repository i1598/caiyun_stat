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
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['tb'] );
	$pos = $result["pos"]; //当前队列消息的读取位置点  
	$json_data = $result["data"]; //当前队列消息的内容  
	
	if($json_data != "HTTPSQS_GET_END" && $json_data != "HTTPSQS_ERROR") {
		//将json格式转换为数组
		$data = json_decode( $json_data, true );
		$data['coop'] = trim(sbc2abc($data ['coop']));
		
		//判断coop是否存在并做相应的操作
		if (! empty ( $data ['coop'] )) {
			/*判断是否有此合作商 -> 
				 先判断缓存里是否存在 -> 不存在 : 查找数据库  存在 : 不做操作
				数据库里是否存在 -> 不存在 : 写入一条新数据 存在 : 读取出 写入缓存 */
			$cache_val = cache_get( 'coopn_'.$data ['coop'], 'default' );
			
			if ($cache_val === false) {
				$sql = "SELECT `id` FROM `" .DB_ALLIANCE. '`.`' .TABLE_ALLIANCE_MEMBER . "` WHERE `username`='" . $data ['coop'] . "'";
				$alliance_member_id = getOne ( $sql, 'alliance' );
				if (empty ( $alliance_member_id )) {
					$data_alliance = array();
					$is_qq = is_numeric($data ['coop']) ? 1 : 0;
					$data_alliance = array ('title' => '-', 'username' => $data ['coop'], 'url' => '-', 'dateline' => TIME, 'is_qq'=>$is_qq );
					$sql = get_insert_sql ( TABLE_ALLIANCE_MEMBER, $data_alliance, DB_ALLIANCE, 'alliance' );
					query ( $sql, 'alliance' );
					$alliance_member_id = last_insert_id( 'alliance' );
				}
				cache_set( 'coopn_'.$data ['coop'], $alliance_member_id, 'default' );
				cache_set( 'coopid_'.$alliance_member_id, $data ['coop'], 'default' );
			}
		}
		
		
		//将tb.caiyun.com传过来的信息插入到原始库中
		if(! empty ( $data )){
					//进行分表操作
					$postfix = $data['ip']%256;
					$table_name = "download_".$postfix; 
					$sql = get_insert_sql( $table_name, $data, DB_DOWNLOAD, 'stat2' );
					query( $sql, 'stat2' );
					
		}
	}else{
		sleep( $sleep_time ); //暂停1秒钟后，再次循环  
	}
}

