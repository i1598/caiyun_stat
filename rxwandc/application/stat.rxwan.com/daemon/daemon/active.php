#!/opt/modules/php-fcgi/bin/php -f 
<?php
//定义加载路径
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'daemon2/common.inc.php');
$sleep_time = 5;

//实例化类
$httpsqs = new httpsqs ();

//守护进程
while ( true ) {
	$result = $httpsqs->pgets ( $cfg_httpsqs ['host'], $cfg_httpsqs ['port'], $cfg_httpsqs ['charset'], $cfg_httpsqs ['active2'] );
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
		//获取数据库
		$db_install = database ( 'install', $data ['uuid'], '2' );
		$db_firstopen = database ( 'firstopen', $data ['uuid'], '2' );
		$db_active = database ( 'active', $data ['uuid'], '2' );
		//获得表名
		$table_install = table ( 'install', $data ['uuid'] );
		$table_firstopen = table ( 'firstopen', $data ['uuid'] );
		$table_active = table ( 'active', $data ['uuid'] );
		//查询安装数据来跟 队列中数据对比 产生shoot_type信息
		$sql = "SELECT * FROM `" . $db_install . "`.`" . $table_install . "` WHERE `uuid`='" . $data ['uuid'] . "'";
		$data_install = getRow ( $sql, 'stat2' );
		$data_unavailable = array(0,13,14,15);
		$data['shoot_type'] = 15;
		$is_firstopen = TRUE;
		if(! empty($data_install)){
			$data['shoot_type'] = 1;
			if($data_install['version'] == $data['version']){
				$data['shoot_type'] = 2;
				if($data_install['is_alive']==1){
					$data['shoot_type'] = 3;
				}			
			}
		}

		//查询第一次打开量(根据传过来的uuid跟版本号)
		$sql = "SELECT * FROM `" . $db_firstopen . "`.`" . $table_firstopen . "` WHERE `uuid`='" . $data ['uuid'] . "' AND `coop`='" . $data['coop'] . "' ORDER BY `dateline` ASC";
		$first_open = getRow ( $sql, 'stat2' );
		$is_firstopen = TRUE;
		if(! empty($first_open)){
			$time_interval = $data['dateline']-$first_open['dateline'];
			if($time_interval>86400){
				$is_version = $first_open['version'] == $data['version'] ? TRUE : FALSE;
				if(empty($data_install)){
					$data['shoot_type'] = $is_version ? 13 : 14;			
				}else{
					$data['shoot_type'] = $is_version ? 5 : 4;
					if($data_install['version'] == $data['version']){
						$data['shoot_type'] = $is_version ? 7 : 6;
						if($data_install['is_alive'] == 1){
							$data['shoot_type'] = $is_version ? 9 : 8;
						}
					}
				}
				$is_firstopen = FALSE;
				//更新install中的active字段
				if(! in_array($data['shoot_type'],$data_unavailable) && $data_install['is_active'] != 1){
					$array = array('is_active'=>1,'time_active'=>TIME);
					$sql_attach = "`uuid`='" . $data ['uuid'] . "'";
					$sql = get_update_sql($table_install,$array,$sql_attach,$db_install,'stat2');
					query($sql,'stat2');
				}
				//写入active
				$sql = get_insert_sql ( $table_active, $data, $db_active, 'stat2' );
				query ( $sql, 'stat2' );
			}
		}
		//安装表里没有存在信息，写入一条新纪录(is_firstopen为1,is_miss为1)
		if(in_array($data['shoot_type'],$data_unavailable)){
				$array = array('uuid'=>$data['uuid'],'ip'=>$data['ip'],'dateline'=>$data['dateline'],'is_alive'=>1,'lastupdate_time'=>$data['dateline'],'version'=>$data['version'],'src'=>$data['src'],'coop'=>$data['coop'],'pkg'=>$data['pkg'],'is_firstopen'=>1,'time_firstopen'=>$data['dateline'],'is_miss'=>1);
				$sql = get_insert_sql($table_install,$array,$db_install,'stat2');
				query($sql,'stat2');
		}
		
		// 如果不存在纯净表中就写入: ractive 要与 active 表结构相同
		$table_ractive = table('ractive', $data['uuid']);
		$sql = "SELECT * FROM ractive2.$table_ractive WHERE `uuid`='".$data['uuid']."'";
		if (!num_rows(query($sql, 'stat2'), 'stat2')) {
			$sql = get_insert_sql ( $table_ractive, $data, 'ractive2', 'stat2' );
			query ( $sql, 'stat2' );
		}
		
		//写入firstopen
		if($is_firstopen){
			if(! in_array($data['shoot_type'],$data_unavailable) && $data_install['is_firstopen'] != 1){
				$array = array('is_firstopen'=>1,'time_firstopen'=>TIME);
				$sql_attach = "`uuid`='" . $data ['uuid'] . "'";
				$sql = get_update_sql($table_install,$array,$sql_attach,$db_install,'stat2');
				query($sql,'stat2');
			}
			$sql = get_insert_sql ( $table_firstopen, $data, $db_firstopen, 'stat2' );
			query ( $sql, 'stat2' );
		}
	} else {
		sleep ( $sleep_time ); //暂停1秒钟后，再次循环  
	}
}

