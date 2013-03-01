<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Base extends Controller {

	public $_response;
	public $_success = false;
	public $_index;
	public $_file = "common";
	public $_data = array();
	public $_totalCount = NULL;
	protected $_msg;
	
	public function __construct(Request $request, Response $response) {
        parent::__construct($request, $response);
        //Session::instance();
		//$this->checkLogin(); 	
    }
	
	protected function checkLogin(){
		
		if(!isset($_SESSION['username'])){
			echo json_encode(array('success'=>'false'));exit;
		}
			
	}

	public function message( $file="" ){
		$file = empty($file) ? $this->_file : $file;
		return Kohana::message( $this->_file, $this->_index );
	}
	
	public function _response( $result=NULL, $file="", $data=array()){
		$this->_response["success"] = is_null($result) ? $this->_success : $result;
		if(!empty($this->_index))
			$this->_response["message"] = $this->message( $file );
		if(!is_null($this->_totalCount))
			$this->_response["results"] = $this->_totalCount;
		$this->_response["data"] = empty($data) ? $this->_data : $data;
		//var_dump($this->request->controller());exit;
		$this->response->body( json_encode($this->_response) );
	}
	
	public function _param( $keys, $default=NULL ){
			
		$data = array();
		if(!empty($keys)){
			foreach($keys as $k=>$key){
				$val = is_array($default) ? $default[$k] : $default;
				
				$data[$key]=Arr::get( $_REQUEST, $key, $val );
					
				
			}
		}
		$data = array_filter($data,array($this,'_filter'));
		
		return $data;
	}
	
	protected function _param1(){
        // $jsonStr = Arr::get($_REQUEST,'json','');
        $jsonStr = file_get_contents("php://input");
        //var_dump($jsonStr);exit;
        return json_decode($jsonStr,true);
    }

	protected function _operation( $model, $type, $data=array(), $key="uuid" ){
    	$id = Arr::get( $_REQUEST, $key );
    	if( is_null( $id ) )	$id = Arr::get( $data, $key );
    	if( isset( $data[$key] )&& $type!='create' ) unset( $data[$key] );
    	$this->_index = $type . ".fail";
    	if($type=="create")
	    	list($id, $row) = $model->add( $data );
	    elseif($type=="edit")
	    	$row = $model->edit( $id, $data );
	    elseif($type=="del")
	    	$row = $model->del( $id, $data );
    	//if(empty($row))
    		//return $this->_response( false );
    	//$this->_index = $type . ".success";
    	//$this->_data = $model->getDetail( $id );
    	//return $this->_response( true );
    }
	
	 protected function _responseBody( $success=false, $param = "", $type="message"){
    	$json = array();
    	$json["success"] = $success;
    	$json[$type]     = empty($param) ? $this->_msg : $param;
    	$json["data"]    = $this->_data;
    	$this->_output( json_encode($json) );
    }
	 
	 protected function _output( $json ){
	 	
    	// $this->response->body( $json );
        echo $json;
    }
	 
	/*
	 * 过滤掉空字符串
	 */ 
	protected function _filter($data1){
		return is_null($data1)?false:true;
	} 	
	
	
	
	
	/**
	 * 验证uuid
	 */
	protected function check_uuid($uuid) {
	    if (! preg_match('/^[a-f0-9]{32}$/',$uuid)) {
	        return false;
	    }
	    
	    return $uuid;
	}
	
	/**
	 * 验证IP
	 */
	protected function check_ip($ip){
	    if ($ip != '127.0.0.1' && $this->is_ip($ip)) {
	        $output = sprintf ( "%u", ip2long($ip) );
	    }else{
	        $output = sprintf ( "%u", ip2long($this->ip()) );
	    }
	    return $output;
	}
	
	/**
	 * IP
	 */
	protected function get_ip(){
	    return sprintf ( "%u", ip2long( $this->ip( ) ) );
	}
	
	/**
	 * 获取IP
	 */
	protected function ip() {
	    $ip = '';
	    if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
	        $ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
	    } elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] )) {
	        $ip = $_SERVER ['HTTP_CLIENT_IP'];
	    } else {
	        $ip = $_SERVER ['REMOTE_ADDR'];
	    }
	    return $ip;
	}
	
	/**
	 * 匹配是否是IP
	 */
	protected function is_ip($ip){
	    $flag = TRUE;
	    $intip = ip2long($ip);
	    if($intip == -1 || $intip === FALSE){
	        $flag = FALSE;
	    }
	    return $flag;
	}
	
	protected function is_version($string){
	    $flag = false;
	    if(preg_match('/^[v0-9\.]{3,20}$/', $string)){
	        if(strpos($string, '.')){ // 以 .开头的字符串，也不算版本
	            $flag = true;
	        }
	    }
	    return $flag;
	}

	protected function explode_package($pkg_name){    
	    $pkg_info = array(
	                    'pkg'=>$pkg_name,
	                    'ver'=>'',
	                    'coop'=>'0' //默认为0，就是我们公司的
	                );
	    $protect_members = array('caiyun', 'update', 'setup', 'install','installer', 'uninstall', 'inst', 'uninst', 'log', 'login', 'beta');
	    $pkg_name = strtolower(trim($this->sbc2abc($pkg_name)));
	    if(!empty($pkg_name)){
	        $pkg_name_info = substr($pkg_name, 0, strlen($pkg_name)-4);
	        $pkg_name_infos = explode('_',$pkg_name_info);
	        $pkg_name_infos_length = count($pkg_name_infos);
	        if($pkg_name_infos_length>2){
	            $coopinfo = $this->coopfilter($pkg_name_infos[$pkg_name_infos_length-1]);
	            if(!in_array($coopinfo, $protect_members)){
	                if($this->is_version($coopinfo)){
	                    $pkg_info['ver']=$coopinfo;
	                }elseif($this->is_cooper($coopinfo, $protect_members)){
	                    $pkg_info['coop'] = $coopinfo;        
	                }
	            }
	            if($this->is_version($pkg_name_infos[$pkg_name_infos_length-2])){
	                $pkg_info['ver'] = $pkg_name_infos[$pkg_name_infos_length-2];
	            }
	        }
	    }    
	    return $pkg_info;
	}

	protected function is_cooper($cooper, $protect_members){
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
	
	protected function sbc2abc($str) {
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
	protected function coopfilter($string){
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
	
	protected function logtofile($file_name='', $title="", $content="", $is_exit=false, $message=''){
	    global $logfile;
	    $output = "======================================\r\n";
	    $output .= date("r")."\t".$title."\r\n";
	    $output .= "Content: ".$content."\r\n";
	    $output .= "--------------------------------------\r\n";
	    if(empty($file_name)){
	        $file_name = $logfile;
	    }
	    file_put_contents($file_name, $output, FILE_APPEND);
	    if ($message) {
	    	echo $message;
	    }
	    if($is_exit){
	        exit;
	    }
	}
}