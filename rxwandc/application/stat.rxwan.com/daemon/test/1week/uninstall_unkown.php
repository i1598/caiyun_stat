<?php
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'test/common.inc.php');

echo "starting uninstall.\n";

$time_span = 604800; //week
$pos_end = strtotime(date('Y-m-d',TIME).' 00:00:00');
$pos_start =  $pos_end - $time_span;

// 首先统计所有的
echo "start total: ".date("r")."\n";
$total = get_total($pos_start, $pos_end);
report($pos_start, 0, $total);

echo "start coop: ".date("r")."\n";
// 然后分合作商统计
// 如果发现数据量过大，超过1万，可以考虑优化id分段
$sql = "SELECT id,username,dateline FROM ".TABLE_ALLIANCE_MEMBER." WHERE is_delete=0 AND is_stop=0";
$query = query($sql, 'alliance');
$total_rows = num_rows($query, 'alliance');
if($total_rows){ //有记录，请导入临时表        
    while(($row = fetch_assoc($query,'alliance'))!==FALSE){  
    	if ($row['dateline']<$today_start) {
    		$total = get_total($pos_start, $pos_end, $row['username']);
	        //echo $row['username'].":$total_coop/";
	        //
	        report($pos_start, $row['id'], $total);
    	}
    }
    free_result($query, 'alliance');
}

echo "End: ".date("r")."\n";

//get_insert_sql($table, $data, $db="", $type='default')
//get_update_sql($table, $data, $attach_sql = '', $db='', $type='default')
// 如果总卸载量为 0，就不要汇报了，没有意义
function report($dateline, $member_id, $quantity){
    if($quantity>0){ 
        $table = 'report_week';
        $sql = "SELECT report_week_id FROM ".TABLE_REPORT_WEEK." WHERE union_member_id='$member_id' AND `dateline`='$dateline'";
        echo $sql."\n";
        $report_day_id = getOne($sql, 'report2');
        echo "Report_ID: $report_day_id.\n";
        $data = array();
        $data['quantity_uninstall_unkown'] = $quantity;
        if(empty($report_day_id)){
            $data['dateline'] = $dateline;
            $data['union_member_id'] = $member_id;
            $sql = get_insert_sql($table, $data, 'reports2', 'report2');
        }else{
            $sql = get_update_sql($table, $data, "`report_week_id`='$report_day_id'",  'reports2', 'report2');
        }
        echo $sql."\n";
        query($sql, 'report2');
    }
	if (IS_DEBUG) {
    	echo "<M:$member_id,D:$quantity>--";
    }
}


function get_total($start, $end, $source=''){
    $total = 0;
    for($i=0;$i<64;$i++){    
        $total += get_total_one($i, $start, $end, $source);
    }
    return $total;
}

// 存活时间是未知的
// 故而，不需要开始和结束。
function get_total_one($table_sn, $start=0, $end=0, $source=''){
    $sql_attach = '';
    if(!empty($source)){
        $sql_attach .= " AND coop='$source'";
    }
    $sql_attach .= " AND shoot_type=0";
    $sql = "SELECT COUNT(DISTINCT `uuid`) FROM `runinstall2`.`uninstall_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach";
    return getOne($sql, 'stat2');
}

