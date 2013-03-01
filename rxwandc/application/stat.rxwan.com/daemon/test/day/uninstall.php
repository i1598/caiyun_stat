<?php
set_time_limit(86400);
define('PATH_ROOT', '/opt/rxwandc/application/stat.rxwan.com/daemon/');
include(PATH_ROOT.'test/common.inc.php');

echo "starting uninstall stat.\n";

$today_start = strtotime(date('Y-m-d',TIME).' 00:00:00');
$yesterday_start = $today_start - 86400;
$pos_start = $yesterday_start;
$pos_end = $pos_start+86400;
//echo date('Y-m-d H:i:s',$pos_end);exit;


//首先基于所有的软件进行循环
$sql = "SELECT `soft_id`,`soft_name`,`dateline` FROM ".TABLE_SOFTWARE;
$query = query($sql, 'reports');
$total_rows = num_rows($query, 'reports');
if($total_rows){
    while(($row = fetch_assoc($query,'reports'))!==FALSE){
    	if ($row['dateline']<$today_start) {
    		//在此下面再循环合作商
    		
    		$sql1 = "SELECT id,username,dateline FROM ".TABLE_ALLIANCE_MEMBER." WHERE is_delete=0 AND is_stop=0";
			$query1 = query($sql1, 'alliance');
			$total_rows1 = num_rows($query1, 'alliance');
			if($total_rows1){ //有记录，请导入临时表
			    while(($row1 = fetch_assoc($query1,'alliance'))!==FALSE){
			    	if($row1['dateline']<$today_start){
				    	$total_coop = get_total($pos_start, $pos_end, $row['soft_id'],$row1['username']);
					   report($pos_start, $row1['id'],$row['soft_id'],$total_coop);
			    	}
			    }
			   // free_result($query, 'alliance');
			}
    	}
    }
    //free_result($query, 'reports');
}

echo "End: ".date("r")."\n";


function report($dateline, $member_id,$soft_id, $quantity){
    if($quantity>0){ 
        $table = 'report_day';
        $sql = "SELECT `report_day_id` FROM ".TABLE_REPORT_DAY." WHERE `soft_id`='".$soft_id."' AND `dateline`='".$dateline."'"." AND `union_member_id`='".$member_id."'";
        $report_day_id = getOne($sql, 'reports');
        $data = array();
        $data['quantity_uninstall'] = $quantity;
        if(empty($report_day_id)){
            $data['dateline'] = $dateline;
            $data['soft_id'] = $soft_id;
			$data['union_member_id'] = $member_id;
            $sql = get_insert_sql($table, $data, 'reports', 'reports');
        }else{
            $sql = get_update_sql($table, $data, "`report_day_id`='".$report_day_id."'",  'reports', 'reports');
        }
        query($sql, 'reports');
    }
}


function get_total($start, $end, $source1='',$source2=''){
    $total = 0;
    for($i=0;$i<256;$i++){
        $total += get_total_one($i, $start, $end, $source1,$source2);
    }
    return $total;
}





function get_total_one($table_sn, $start=0, $end=0, $source1='',$source2=''){
    $sql_attach = '';
    if(!empty($source1)){
        $sql_attach .= " AND `softid`='$source1'";
    }
	
	if(!empty($source1)){
        $sql_attach .= " AND `coop`='$source2'";
    }
	$sql = "SELECT COUNT(DISTINCT `uuid`) FROM `uninstall`.`uninstall_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach";
    return getOne($sql, 'stat');
}

