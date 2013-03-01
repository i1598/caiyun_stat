<?php
set_time_limit(86400);
define('PATH_ROOT', '/opt/rxwandc/application/ad.rxwan.com/daemon/');
include(PATH_ROOT.'test/common.inc.php');

/**
 * 这里要首先写一个任务计划是每天的统计
 */

echo "starting advert_day stat.\n";
$time_start = strtotime(date('Y-m-d',TIME-86400)." 00:00:00"); 
$time_end = strtotime(date('Y-m-d',TIME-86400)." 23:59:59");
$pos_start = strtotime(date('Y-m-d',TIME-86400)."00:00:00");//统计的参照物
//echo date('Y-m-d H:i:s',$pos_end);exit;


echo "start time: ".date("Y-m-d H:i:s")."\n";
//首先基于所有的广告进行循环
$sql = "SELECT `id` FROM ".TABLE_ADVERTISE;
$query = query($sql, 'reports');
$total_rows = num_rows($query, 'reports');
if($total_rows){
    while(($row = fetch_assoc($query,'reports'))!==FALSE){
    		$data = array();
			$data['advert_id'] = $row['id'];	
    		//针对单个广告的统计
    		$show_db = "advert_show";
	    	$click_db = "advert_click";
			//统计表
			$advert_day_table = "advert_report_day";
			//统计广告展现
	    	$data['uv'] = get_total($show_db,$time_start, $time_end, $row['id'],1);
	       	$data['pv'] = get_total($show_db,$time_start, $time_end, $row['id']);
			//统计广告点击
			$data['click_unique'] = get_total($click_db,$time_start, $time_end, $row['id'],1);
			$data['click'] = get_total($click_db,$time_start, $time_end, $row['id']);
			
			//分别赋值
			
	       	report($advert_day_table,$pos_start,$data);
			    		
    }
    free_result($query, 'reports');
}

echo "End: ".date("Y-m-d H:i:s")."\n\n\n\n";


function report($table,$dateline, $data){
    if(!empty($data)){ 
        $sql = "SELECT `advert_id` FROM ".$table." WHERE `advert_id`='".$data['advert_id']."' AND `referer_dateline`='".$dateline."'";
        $get_advert_id = getOne($sql, 'reports');
        if(empty($get_advert_id)){
            $data['referer_dateline'] = $dateline;
			$data['dateline'] = time();
            $sql = get_insert_sql($table, $data, 'reports', 'reports');
        }else{
        	$data['dateline'] = time();
            $sql = get_update_sql($table, $data, "`advert_id`='".$get_advert_id."' AND `referer_dateline` = '".$dateline."'",  'reports', 'reports');
        }
        query($sql, 'reports');
    }
}
/**
 * 
 */
function get_total($db,$start, $end, $source='',$flag=""){
    $total = 0;
    for($i=0;$i<256;$i++){    
        $total += get_total_one($db, $i, $start, $end, $source,$flag);
    }
    
    return $total;
}

function get_total_one($db_sn,$table_sn, $start=0, $end=0, $source='',$flag=""){
    $sql_attach = '';
	
	if(!empty($source)){
        $sql_attach .= " AND `advert_id`='$source'";
    }
	if(!empty($flag)){
		$field = "COUNT(DISTINCT `ip`)";
	}else{
		$field = " COUNT(`ip`) ";
	}
    $sql = "SELECT $field FROM `$db_sn`.`$db_sn"._."$table_sn` WHERE `dateline`>='$start' AND `dateline`<='$end' $sql_attach";
    return getOne($sql, 'reports');
}
