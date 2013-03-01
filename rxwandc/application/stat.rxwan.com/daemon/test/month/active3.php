#!/opt/modules/php-fcgi/bin/php -f 
<?php
define('PATH_ROOT', '/opt/sbin/daemon/report/');
include(PATH_ROOT.'test/common.inc.php');

echo "starting active.\n";
// 每个月的1号触发本脚本
$pos_end = strtotime(date('Y-m-d',TIME).' 00:00:00');
$pos_start =  strtotime(date('Y-m',TIME-2160000).'-01 00:00:00');// 86400*25=2160000, 倒退25天的月


// 首先统计所有的
echo "start total: ".date("r", TIME)."\n";
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

echo "End Active: ".date("r")."\n";


//get_insert_sql($table, $data, $db="", $type='default')
//get_update_sql($table, $data, $attach_sql = '', $db='', $type='default')
// 如果总卸载量为 0，就不要汇报了，没有意义
function report($dateline, $member_id, $quantity){
    if($quantity>0){ 
        $table = 'report_month';
        $sql = "SELECT report_month_id FROM ".TABLE_REPORT_MONTH." WHERE union_member_id='$member_id' AND `dateline`='$dateline'";
        $report_day_id = getOne($sql, 'report2');
        echo "Report_ID: $report_day_id.\n";
        $data = array();
        $data['quantity_active_3'] = $quantity;
        if(empty($report_day_id)){
            $data['dateline'] = $dateline;
            $data['union_member_id'] = $member_id;
            $sql = get_insert_sql($table, $data, 'reports2', 'report2');
        }else{
            $sql = get_update_sql($table, $data, "`report_month_id`='$report_day_id'",  'reports2', 'report2');
        }
        query($sql, 'report2');
    }
	if (IS_DEBUG) {
    	echo "<M:$member_id,D:$quantity>--";
    }
    
}


function get_total($start, $end, $source=''){
    $total = 0;
    for($d=0;$d<16;$d++){
        for($i=0;$i<256;$i++){    
            $total += get_total_one($d, $i, $start, $end, $source);
        }
        echo '-';
    }
    
    return $total;
}

//总安装量，包含卸载量，故而不是净安装量
function get_total_one($db_sn, $table_sn, $start=0, $end=0, $source=''){
    $sql_attach = '';
    if(!empty($source)){
        $sql_attach .= " AND coop='$source'";
    }
    //$sql = "SELECT COUNT(DISTINCT `uuid`) FROM `active2_$db_sn`.`active_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach";
    $sql = "SELECT COUNT( * ) AS total FROM (SELECT count( `uuid` ) AS cnt FROM `active2_$db_sn`.`active_$table_sn` WHERE `dateline`>='$start' AND `dateline`<'$end' $sql_attach GROUP BY uuid HAVING cnt >=3)A";
    return getOne($sql, 'stat2');
}