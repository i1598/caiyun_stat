<?php

// firstopen_ --- 256
// active_  --- 256
// install_ --- 256
// reinstall_ --- 256 (升级的重复安装会导致很多)
// uninstall_  --- 256
// reuninstall_  --- 256 (暂时不用)
function table($table, $key='', $postfix=''){
    $numerator = hexdec(substr($key, 0, 3));
    switch($table){        
        case 'uninstall':
        case 'firstopen':
        case 'reinstall':
        case 'install':
        case 'server':
        case 'active':
            $factor = 256;
            break;
        case 'ractive':
        case 'ractive2':	
        	$table = 'active';
            $factor = 256;
            break;    
        case 'uslib':
            $factor = 32;
            break;
        case 'runinstall':    
        case 'runinstall2':
        	$table = 'uninstall';
        	$factor = 64;
        	break;
        case 'reuninstall':
        case 'reuninstall2':
        	$table = 'uninstall';
        	$factor = 128;    
    }
    return $table.'_'.($numerator%$factor);

}

function database($db, $key='', $postfix=''){
    $numerator = hexdec(substr($key, 4, 1));
    $db_postfix = $db=='active'?'_'.($numerator%16):'';
    return $db.$postfix.$db_postfix;
}


function msg_die($msg){
    exit($msg);
}

function dblink($type='default', $is_new=false){
    global $cfg_db;
    $cfg_db[$type]['link'] = mysql_connect($cfg_db[$type]['host'].':'.$cfg_db[$type]['port'], $cfg_db[$type]['username'], $cfg_db[$type]['password'], $is_new) or msg_die("\nMySQL has got an error. ErrorNO: ".mysql_errno($cfg_db[$type]['link']).',ErrorMsg:'.mysql_error($cfg_db[$type]['link']).".\n");
    mysql_query("SET NAMES '".$cfg_db[$type]['charset']."'", $cfg_db[$type]['link']);
    mysql_select_db($cfg_db[$type]['dbname'], $cfg_db[$type]['link']); //dbname
    return $cfg_db[$type]['link'];
}

function dbclose($type='default'){
    global $cfg_db; 
    return mysql_close($cfg_db[$type]['link'] );
}

function query($sql, $type='default'){
    global $cfg_db;
    $flag = false;
    if(!isset($cfg_db[$type]['link'])){
        $flag = true;
    }else{
        if(mysql_ping($cfg_db[$type]['link'])===false){
            $flag = true;
        }
    }
    if($flag){
        $cfg_db[$type]['link'] = dblink($type, true);
    }
    //不停执行，以保证在同一个库里面
    mysql_select_db($cfg_db[$type]['dbname'], $cfg_db[$type]['link']); //dbname
    $query = mysql_query($sql,$cfg_db[$type]['link']);
    if($query===false){
        echo "\nError in Query.MySQL has got an error. ErrorNO: ".mysql_errno($cfg_db[$type]['link']).',ErrorMsg:'.mysql_error($cfg_db[$type]['link']).".\n";
        echo "SQL:$sql.\n";
        exit;
    }
    return $query;
}

function getOne($sql, $type='default'){
    global $cfg_db;
    $row  = '';
    $query = query($sql .' LIMIT 0,1', $type);
    if($query && mysql_num_rows($query)){
        $row = mysql_result($query, 0);
        mysql_free_result($query);
    }
    return $row;
}


function getRow($sql, $type='default'){
    global $cfg_db;
    $result = array();
    $query = query($sql.' LIMIT 0,1', $type);
    if($query && mysql_num_rows($query)){
        $data = array();
        while(($row = mysql_fetch_assoc($query))!==FALSE){
            $data[] = $row;
        }
        mysql_free_result($query);
        $result = $data[0];
    }
    return $result;
}

function getAll($sql, $type='default'){
    global $cfg_db;
    $result = array();
    $query = query($sql, $type);
    if($query && mysql_num_rows($query)){
        while(($row = mysql_fetch_assoc($query))!==FALSE){
            $result[] = $row;
        }
        mysql_free_result($query);
    }
    return $result;
}


function free_result($result, $type='default'){
    return mysql_free_result ($result);
}

function fetch_assoc($result, $type='default'){
    return mysql_fetch_assoc($result);
}

function num_rows($result, $type='default'){
    return mysql_num_rows($result);
}

function last_insert_id($type='default'){
    global $cfg_db;
    return mysql_insert_id($cfg_db[$type]['link']);
}


function logtofile($file_name='', $title="", $content="", $is_exit=false){
    global $logfile;
    $output = "======================================\r\n";
    $output .= date("r")."\t".$title."\r\n";
    $output .= "Content: ".$content."\r\n";
    $output .= "--------------------------------------\r\n";
    if(empty($file_name)){
        $file_name = $logfile;
    }
    file_put_contents($file_name, $output, FILE_APPEND);
    if($is_exit){
        exit;
    }
}

function get_insert_sql($table, $data, $db="", $type='default'){
    global $cfg_db;
    $keys = $values = array();
    foreach($data as $k=>$d){
        $keys[] = $k;
        $values[] = mysql_real_escape_string($d, $cfg_db[$type]['link']);
    }

    return "INSERT INTO `$db`.`$table`(`".implode('`,`', $keys)."`) VALUES('".implode("','",$values)."')";
}


function get_update_sql($table, $data, $attach_sql = '', $db='', $type='default'){
    global $cfg_db;
    if(!count($data)){
        return false;
    }
    $sets = array();
    foreach($data as $k=>$d){
        $sets[] = '`'.$k.'`=\''.mysql_real_escape_string($d, $cfg_db[$type]['link']).'\'';
    }
    if(!empty($attach_sql)){
        $attach_sql = ' AND '.$attach_sql;
    }
    return "UPDATE `$db`.`$table` SET ".implode(', ',$sets).' WHERE 1 '.$attach_sql;
}


function cache_link($type='default'){
	global $cfg_cache;
	$craw_name  = 'memcache_'.$type;
	if (!is_object($$craw_name)) {
		$$craw_name = new Memcache;
		$$craw_name->addServer($cfg_cache[$type]['host'],$cfg_cache[$type]['port']);
	}
	return $cfg_cache[$type]['link'] = $$craw_name;
}

function cache_get($key, $type='default'){
	global $cfg_cache;
	if(!isset($cfg_cache[$type]['link'])){
		cache_link($type);
	}
	return $cfg_cache[$type]['link']->get($cfg_cache[$type]['prefix'].$key);
}

function cache_set($key, $value, $type='default', $expire_time=0, $is_compressed=0 ){
	global $cfg_cache;
	if(!isset($cfg_cache[$type]['link'])){
		cache_link($type);
	}
    $expire_time = empty($expire_time)?0:$expire_time;
	return $cfg_cache[$type]['link']->set($cfg_cache[$type]['prefix'].$key, $value, $is_compressed, $expire_time);
}

///////////////////////////
///////////////////////////
///////////////////////////
///////////////////////////

function crand($min, $max){
    list($usec, $sec) = explode(' ', microtime());
    $seed =(float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    return mt_rand($min, $max);
}


function is_md5str($string=""){
    return preg_match('/^[a-f0-9]{32}$/', $string);
}


function is_version($string){
    $flag = false;
    if(preg_match('/^[v0-9\.]{3,20}$/', $string)){
        if(strpos($string, '.')){ // 以 .开头的字符串，也不算版本
            $flag = true;
        }
    }
    return $flag;
}


function explode_package($pkg_name){    
    $pkg_info = array(
                    'pkg'=>$pkg_name,
                    'ver'=>'',
                    'coop'=>'' //默认为空，就是我们公司的
                );
    $protect_members = array('caiyun', 'update', 'setup', 'install','installer', 'uninstall', 'inst', 'uninst', 'log', 'login', 'beta');
    $pkg_name = strtolower(trim(sbc2abc($pkg_name)));
    if(!empty($pkg_name)){
        $pkg_name_info = substr($pkg_name, 0, strlen($pkg_name)-4);
        $pkg_name_infos = explode('_',$pkg_name_info);
        $pkg_name_infos_length = count($pkg_name_infos);
        if($pkg_name_infos_length>2){
            $coopinfo = coopfilter($pkg_name_infos[$pkg_name_infos_length-1]);
            if(!in_array($coopinfo, $protect_members)){
                if(is_version($coopinfo)){
                    $pkg_info['ver']=$coopinfo;
                }elseif(is_cooper($coopinfo, $protect_members)){
                	$pkg_info['coop'] = $coopinfo;    	
                }
            }
            if(is_version($pkg_name_infos[$pkg_name_infos_length-2])){
                $pkg_info['ver'] = $pkg_name_infos[$pkg_name_infos_length-2];
            }
        }
    }    
    return $pkg_info;
}

function is_cooper($cooper, $protect_members){
	$flag = strlen($cooper)>1? true:false;
	if ($flag) {
		foreach ($protect_members as $protect_member){
			if(strpos($cooper, $protect_member)!==FALSE){ //只要出现，就不是了
				$flag = false;
				break;
			}
		}	
	}
	return $flag;
}



function sbc2abc($str) {
		$f = array ('　', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', '．', '－', '＿', '＠',  '（', '）', '【', '】', '［', '］', '｛', '｝', '＝', '＋', '：', '；', '，', '。','《', '》');
		$t = array (' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '.', '-', '_', '@' , '(', ')', '[', ']', '[', ']','{','}','=','+',':',';',',','.','<','>');
		$str = str_replace ( $f, $t, $str );
		return $str;
}


// sbc2abc 在原始字符串的时候，就替换过了
// 过滤掉非字母等字符，并且输出全部是小写
// 过滤掉了空格和汉字等
// 从左到右，会过滤掉后面的非法字符，也就是左合法，到右边，依次不合法
// 例如：asdfasf我要合作商---> asdfasf, 我要mdsfuowef合作msfasom商---->mdsfuowef
function coopfilter($string){
    $coops = explode('.exe', strtolower($string));
    $string = preg_replace(array('/(?:\[|\(|\{)[0-9a-z\.\-\_\,\;\[\{\(\)\}\]]+(?:\}|\)|\])/','/(?:\[|\(|\{)/','/(?:\}|\)|\])/' ), '', $coops[0]);
	$length = strlen($string);
	$avaliable_alpha = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '-', '_', '@' ,'.');
	$output = '';
	for ($i = 0; $i < $length; $i++) {
		if (in_array($string[$i], $avaliable_alpha)) {
			$output .= $string[$i];
		}else{
            if(!empty($output)){
                break;
            }
        }
	}
	return trim($output);
}