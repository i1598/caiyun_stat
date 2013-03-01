#!/opt/modules/php-fcgi/bin/php -f 
<?php
//定义加载路径
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'daemon2/common.inc.php');
$sleep_time = 60;
$time_last_install =  1270051200; //如果彻底找不到，就认为是很久很久以前安装的
//实例化类
$httpsqs = new httpsqs ();

//守护进程
while ( true ) {
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['uninstall2'] );
	$pos = $result ["pos"]; //当前队列消息的读取位置点  
	$json_data = $result ["data"]; //当前队列消息的内容  
	if ($json_data != "HTTPSQS_GET_END" && $json_data != "HTTPSQS_ERROR") {
		//将json格式转换为数组
		$data = json_decode ( $json_data, true );
		$data ['coop'] = trim(sbc2abc($data ['coop']));
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
		//获得数据库
		$db_install = database ( 'install2', $data ['uuid'] );
		$db_uninstall = database ( 'uninstall2', $data ['uuid'] );
		$db_firstopen = database ( 'firstopen2', $data ['uuid'] );
		$db_reinstall = database ( 'reinstall2', $data ['uuid'] );
		//获得表名
		$table_install = table ( 'install', $data ['uuid'] );
		$table_firstopen = table ( 'firstopen', $data ['uuid'] );
		$table_uninstall = table ( 'uninstall', $data ['uuid'] );
		$table_reinstall = table ( 'reinstall', $data ['uuid'] );
		//SQL语句(根据传过来的uuid 查询uuid是否存在  )
		$sql = "SELECT * FROM `" . $db_install . "`.`" . $table_install . "` WHERE `uuid`='" . $data ['uuid'] . "'";
		$data_install = getRow ( $sql, 'stat2' );
		if(empty($data_install)){
			$shoot_type = 0;
		}else{
			$shoot_type = 3;
			$time_last_install = $data_install['dateline'];
			if($data_install['is_alive']){				
				$shoot_type = 4;
				if($data_install['version'] == $data['version']){
					$shoot_type = 5;
				}
			}else{ 
				$sql_last_install = "SELECT * FROM `" . $db_reinstall . "`.`" . $table_reinstall . "` WHERE `uuid`='" . $data ['uuid'] . "' AND `dateline`<'".$data['dateline']."' ORDER BY dateline DESC";
				$data_reinstall = getRow($sql_last_install, 'stat2');
				if(!empty($data_reinstall)){
					$time_last_install = $data_reinstall['dateline'];
					$shoot_type = $data_reinstall['version']==$data['version'] ? 2 : 1;
				}
			}
		}
		$update_data_install = array('is_alive'=>0,'lastupdate_time'=>$data ['dateline']);
		$update_where_install = "`uuid`='" . $data ['uuid'] . "'";
		$sql = get_update_sql ( $table_install, $update_data_install, $update_where_install, $db_install, 'stat2' );
		query ( $sql, 'stat2' );
		//写入卸载表
		$data ['livetime'] = $data ['dateline'] - $time_last_install;
		$data ['shoot_type'] = $shoot_type;
		$sql = get_insert_sql ( $table_uninstall, $data, $db_uninstall, 'stat2' );
		query ( $sql, 'stat2' );
		
		// 净增卸载(默认 install和reinstall 模式) --- start
		$table_reuninstall = table('reuninstall', $data ['uuid']);
		$table_runinstall = table('runinstall', $data ['uuid']);
		$sql = "SELECT * FROM runinstall2.$table_runinstall WHERE uuid='".$data ['uuid']."'";
		if (num_rows(query($sql, 'stat2'), 'stat2')) {
			$sql = get_insert_sql($table_reuninstall, $data, 'reuninstall2', 'stat2');
			query($sql, 'stat2');
		}else{
			$sql = get_insert_sql($table_runinstall, $data, 'runinstall2', 'stat2');
			query($sql, 'stat2');
		}
		// 净增卸载(默认 install和reinstall 模式) --- end
		
	} else {
		sleep ( $sleep_time ); //暂停1秒钟后，再次循环  
	}
}
