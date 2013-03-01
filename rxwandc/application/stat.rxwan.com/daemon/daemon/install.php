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
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['install2'] );
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
		//获得数据库和表名
		$db_install = database ( 'install2', $data ['uuid'] );
		$db_reinstall = database ( 'reinstall2', $data ['uuid'] );
		$table_reinstall = table ( 'reinstall', $data ['uuid'] );
		$table_install = table ( 'install', $data ['uuid'] );
		//SQL语句(根据传过来的uuid 查询uuid是否存在  )
		$sql = "SELECT * FROM `" . $db_install . "`.`" . $table_install . "` WHERE `uuid`='" . $data ['uuid'] . "'";
		$data_install = getRow ( $sql, 'stat2' );
		//如果有值的的话	
		if (! empty ( $data_install )) {
			//存在的话把获得的信息存入reinstall表
			$sql = get_insert_sql ( $table_reinstall, $data_install, $db_reinstall, 'stat2' );
			query ( $sql, 'stat2' );
			//更新安装表
			$update_data = array ('is_alive' => 1, 'lastupdate_time' => $data ['dateline'] );
			$update_where = "`uuid`='" . $data ['uuid'] . "'";
			$sql = get_update_sql ( $table_install, $update_data, $update_where, $db_install, 'stat2' );
			query ( $sql, 'stat2' );
		} else {
			$data ['is_alive'] = 1;
			$data ['lastupdate_time'] = $data ['dateline'];
			$sql = get_insert_sql ( $table_install, $data, $db_install, 'stat2' );
			query ( $sql, 'stat2' );
		}
	} else {
		sleep ( $sleep_time ); //暂停1秒钟后，再次循环  
	}
}
