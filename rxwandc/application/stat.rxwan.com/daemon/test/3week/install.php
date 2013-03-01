<?php
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'test/common.inc.php');

echo "starting install.\n";

$week_sn = date('W', TIME-86400);
if($week_sn%3!=0){
	exit("Not available.<$week_sn>\n");
}

$time_span = 1814400; //604800*3week
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
		if($row['dateline']<$today_start){
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
function report($dateline, $member_id, $quantity){
    if($quantity>0){ 
        $table = 'report_3week';
        $sql = "SELECT report_3week_id FROM ".TABLE_REPORT_3WEEK." WHERE union_member_id='$member_id' AND `dateline`='$dateline'";
        echo $sql."\n";
        $report_day_id = getOne($sql, 'report2');
        echo "Report_ID: $report_day_id.\n";
        $data = array();
        $data['quantity_install'] = $quantity;
        if(empty($report_day_id)){
            $data['dateline'] = $dateline;
            $data['union_member_id'] = $member_id;
            $sql = get_insert_sql($table, $data, 'reports2', 'report2');
        }else{
            $sql = get_update_sql($table, $data, "`report_3week_id`='$report_day_id'",  'reports2', 'report2');
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
    for($i=0;$i<256;$i++){
        $total += get_total_one($i, $start, $end, $source);
    }
    return $total;
}

//总安装量，包含卸载量，故而不是净安装量
function get_total_one($table_sn, $start=0, $end=0, $source=''){
    $sql_attach = '';
    if(!empty($source)){
        $sql_attach .= " AND coop='$source'";
    }
    $sql = "SELECT COUNT(DISTINCT `uuid`) FROM `install2`.`install_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach";
    return getOne($sql, 'stat2');
}
