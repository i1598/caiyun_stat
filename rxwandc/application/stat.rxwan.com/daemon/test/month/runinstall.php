<?php
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'test/common.inc.php');

echo "starting uninstall.\n";
// 每个月的1号触发本脚本
$pos_end = strtotime(date('Y-m-d',TIME).' 00:00:00');
$pos_start =  strtotime(date('Y-m',TIME-2160000).'-01 00:00:00');// 86400*25=2160000, 倒退25天的月

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
    		$total_coop = get_total($pos_start, $pos_end, $row['username']);
	        //echo $row['username'].":$total_coop/";
	        //
	        report($pos_start, $row['id'], $total_coop);
    	}
    }
    free_result($query, 'alliance');
}

echo "End: ".date("r")."\n";

//get_insert_sql($table, $data, $db="", $type='default')
//get_update_sql($table, $data, $attach_sql = '', $db='', $type='default')
// 如果总卸载量为 0，就不要汇报了，没有意义
function report($dateline, $member_id, $quantity_runinstall){
    if($quantity_runinstall>0){ 
        $table = 'report_month';
        $sql = "SELECT report_month_id FROM ".TABLE_REPORT_MONTH." WHERE union_member_id='$member_id' AND `dateline`='$dateline'";
        echo $sql."\n";
        $report_day_id = getOne($sql, 'report2');
        echo "Report_ID: $report_day_id.\n";
        $data = array();
        $data['quantity_runinstall'] = $quantity_runinstall;
        if(empty($report_day_id)){
            $data['dateline'] = $dateline;
            $data['union_member_id'] = $member_id;
            $sql = get_insert_sql($table, $data, 'reports2', 'report2');
        }else{
            $sql = get_update_sql($table, $data, "`report_month_id`='$report_day_id'",  'reports2', 'report2');
        }
        echo $sql."\n";
        query($sql, 'report2');
    }
	if (IS_DEBUG) {
    	echo "<M:$member_id,D:$quantity_runinstall>--";
    }
    
}


function get_total($start, $end, $source=''){
    $total = 0;
    for($i=0;$i<64;$i++){    
        $total += get_total_one($i, $start, $end, $source);
    }
    return $total;
}

//其实这里不用再次  DISTINCT 进行排重了
function get_total_one($table_sn, $start=0, $end=0, $source=''){
    $sql_attach = '';
    if(!empty($source)){
        $sql_attach .= " AND coop='$source'";
    }
    $sql = "SELECT COUNT(DISTINCT `uuid`) FROM `runinstall2`.`uninstall_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach";
    return getOne($sql, 'stat2');
}


