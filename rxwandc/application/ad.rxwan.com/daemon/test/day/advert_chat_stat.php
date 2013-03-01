<?php
set_time_limit(86400);
define('PATH_ROOT', '/opt/rxwandc/application/ad.rxwan.com/daemon/');
include(PATH_ROOT.'test/common.inc.php');

/**
 * 这里要首先写一个任务计划是每隔一个小时的统计
 */

echo "starting advert_chat stat.\n";
$hour = date('H',TIME)-1;
if($hour<0){
	$hour=23;
	$time_start = strtotime(date('Y-m-d',TIME-86400)." $hour:00:00");
	$time_end = strtotime(date('Y-m-d',TIME-86400)." $hour:59:59");
	$pos_start = strtotime(date('Y-m-d',TIME-86400)."00:00:00");//统计的标识
}else{
	$time_start = strtotime(date('Y-m-d',TIME)." $hour:00:00");
	$time_end = strtotime(date('Y-m-d',TIME)." $hour:59:59");
	$pos_start = strtotime(date('Y-m-d',TIME)."00:00:00");//统计的标识		
}

//echo date('Y-m-d H:i:s',$pos_end);exit;


echo "start time: ".date("Y-m-d H:i:s")."\n";
//首先基于所有的广告进行循环
$sql = "SELECT `id` FROM ".TABLE_ADVERTISE;
$query = query($sql, 'reports');
$total_rows = num_rows($query, 'reports');
if($total_rows){
    while(($row = fetch_assoc($query,'reports'))!==FALSE){
    		//针对单个广告的统计
    		$show_db = "advert_show";
	    	$click_db = "advert_click";
			//统计表
			$advert_show_table = "advert_show_report_chat";
			$advert_click_table = "advert_click_report_chat";
			//统计广告展现
	    	$show_stat = get_total($show_db,$time_start, $time_end, $row['id']);
	       	report($advert_show_table,$pos_start, $row['id'],$show_stat);
			//统计广告点击
			$click_stat = get_total($click_db,$time_start, $time_end, $row['id']);
	       	report($advert_click_table,$pos_start, $row['id'],$click_stat);
			    		
    }
    free_result($query, 'reports');
}

echo "End: ".date("Y-m-d H:i:s")."\n\n\n\n";


function report($table,$dateline, $advert_id, $quantity){
    if($quantity>0){ 
        $sql = "SELECT `advert_id` FROM ".$table." WHERE `advert_id`='".$advert_id."' AND `dateline`='".$dateline."'";
        $get_advert_id = getOne($sql, 'reports');
        $data = array();
		//拼统计字段
		$hour = date('H',TIME)-1;
		if($hour<0){
			$hour=23;
		}
		$zone_field = "quantity_".$hour; 
        $data[$zone_field] = $quantity;
        if(empty($get_advert_id)){
            $data['dateline'] = $dateline;
            $data['advert_id'] = $advert_id;
            $sql = get_insert_sql($table, $data, 'reports', 'reports');
        }else{
            $sql = get_update_sql($table, $data, "`advert_id`='".$get_advert_id."' AND `dateline` = '".$dateline."'",  'reports', 'reports');
        }
        query($sql, 'reports');
    }
}

function get_total($db,$start, $end, $source=''){
    $total = 0;
    for($i=0;$i<256;$i++){    
        $total += get_total_one($db, $i, $start, $end, $source);
    }
    
    return $total;
}

function get_total_one($db_sn,$table_sn, $start=0, $end=0, $source=''){
    $sql_attach = '';
	
	if(!empty($source)){
        $sql_attach .= " AND `advert_id`='$source'";
    }
	
    $sql = "SELECT COUNT(`ip`) FROM `$db_sn`.`$db_sn"._."$table_sn` WHERE `dateline`>='$start' AND `dateline`<='$end' $sql_attach";
    return getOne($sql, 'reports');
}
