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

	
	
	public function message( $file="" ){
		$file = empty($file) ? $this->_file : $file;
		return Kohana::message( $this->_file, $this->_index );
	}

	protected function _debug( $param, $exit = false ){
		echo "<pre>";
		var_dump( $param );
		if( $exit ) exit;
	}
	
	public function _response( $result=NULL, $file="", $data=array()){
		$this->_response["success"] = is_null($result) ? $this->_success : $result;
		if(!empty($this->_index))
			$this->_response["message"] = $this->message( $file );
		if(!is_null($this->_totalCount))
			$this->_response["results"] = $this->_totalCount;
		$this->_response["data"] = empty($data) ? $this->_data : $data;
		$this->response->body( json_encode($this->_response) );
	}

	
	public function _param( $keys, $default=NULL ){
		$data = array();
		if(!empty($keys)){
			foreach($keys as $k=>$key){
				$val = is_array($default) ? $default[$k] : $default;
				$data[$key] = Arr::get( $_REQUEST, $key, $val );
			}
		}
		
		//执行数据过滤
		$data = array_filter($data,array($this,'_filter'));
		return $data;
	}
	
	protected function _param1(){
        // $jsonStr = Arr::get($_REQUEST,'json','');
        $jsonStr = file_get_contents("php://input");
        //var_dump($jsonStr);exit;
        return json_decode($jsonStr,true);
    }
	
	
	protected function _operation( $model, $type, $data=array(), $key="id",$param='' ){
    	$id = Arr::get( $_REQUEST, $key );
    	if( is_null( $id ) )	$id = Arr::get( $data, $key );
    	if( isset( $data[$key] ) ) unset( $data[$key] );
    	$this->_index = $type . ".fail";
    	if($type=="create")
	    	list($id, $row) = $model->add( $data );
	    elseif($type=="edit")
	    	$row = $model->edit( $id, $data,$param );
	    elseif($type=="del")
	    	$row = $model->del( $id, $data );
    	if(empty($row))
    		return $this->_response( false );
    	$this->_index = $type . ".success";
    	$this->_data = $model->getDetail( $id );
    	return $this->_response( true );
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
		return is_null($data1)||$data1===""?false:true;
	} 	
	
	/**
	 * 
	 */
	protected function getFirstWord($s0){
		$fchar = ord($s0{0});  //获取字符串的第一个字节
		//判断是不是英文字符,如果是则直接返回该字母大写
		if ($fchar >= ord('A') and $fchar <= ord('z'))return strtoupper($s0{0});
		//判断是否为数字，如果是的话则直接返回数字的
		if ($fchar >= 48 and $fchar <= 57)return strtoupper($s0{0});
		//以下代码用字符编码转换函数，通过两种字符集编码转换的对比，判断字符串是哪种字符集
		//最终取字符串为GB字符集
		$s1 = @iconv('UTF-8', 'GBK', $s0);
		$s2 = @iconv('GBK', 'UTF-8', $s1);
		if ($s2 == $s0) {
		$s = $s1;
		} else {
		$s = $s0;
		}
		
		//计算给出的字符串的前两个字节内码，然后再根据结果判断在GB字符集中的位置，从而根据位置与拼音的关系，最终得出拼音字母
			$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		
			if($asc >= -20319 and $asc <= -20284) return "A";
		    if($asc >= -20283 and $asc <= -19776) return "B";
		    if($asc >= -19775 and $asc <= -19219) return "C";
		    if($asc >= -19218 and $asc <= -18711) return "D";
		    if($asc >= -18710 and $asc <= -18527) return "E";
		    if($asc >= -18526 and $asc <= -18240) return "F";
		    if($asc >= -18239 and $asc <= -17923) return "G";
			if($asc>=-17922   and $asc <=-17418)  return "H";
		    if($asc >= -17417 and $asc <= -16475) return "J";
		    if($asc >= -16474 and $asc <= -16213) return "K";
		    if($asc >= -16212 and $asc <= -15641) return "L";
		    if($asc >= -15640 and $asc <= -15166) return "M";
		    if($asc >= -15165 and $asc <= -14923) return "N";
		    if($asc >= -14922 and $asc <= -14915) return "O";
		    if($asc >= -14914 and $asc <= -14631) return "P";
		    if($asc >= -14630 and $asc <= -14150) return "Q";
		    if($asc >= -14149 and $asc <= -14091) return "R";
		    if($asc >= -14090 and $asc <= -13319) return "S";
		    if($asc >= -13318 and $asc <= -12839) return "T";
		    if($asc >= -12838 and $asc <= -12557) return "W";
		    if($asc >= -12556 and $asc <= -11848) return "X";
		    if($asc >= -11847 and $asc <= -11056) return "Y";
		    if($asc >= -11055 and $asc <= -10247) return "Z";
		    return null;
	}
	
	/**
	 * 写入日志文件
	 */
	 
	 // public function __destruct(){
// 	 	
			// $controller = $this->request->controller();	
			// $action = $this->request->action();
			// $act = $this->request->param('act');
			// $type = $this->request->param('type');
			// $s = $this->request->param('s');
// 			
			// //判断是否是list的路由如果是的话，就跳出
			// if($act=='list' || $act == 'lists' || $action =='list' || $action =='ledger'|| $controller == 'toolbar')
				// //echo $act.'<br/><br/><br/>'.$action.'<br/><br/><br/>';
				// return;
			// //如果有增删改操作的话，执行日志写入	
			// $username=Session::instance()->get('username');
// 			
			// if(is_array($this->_data))
				// $data = json_encode($this->_data);
			// //封装日期
			// $date = date("Y-m-d H:i:s",time());
// 			
// 			
			// //定义message文件
			// $this->_file="adminlog";
// 			
			// /**
			 // * 根据路由来封装日志信息
			 // */
			  // switch($controller){
					// case 'game':
					// case 'operator':
					// case 'mapping':
						// $this->_index=$controller.".".$act;
						// break;
					// case 'opsys':
						// $this->_index = $controller.".".$type.".".$act;
						// break;
					// case 'user':
					// case 'accounts':	
						// $this->_index = $controller.".".$action;
						// break;
			  // }
// 			  
			  // $msg = $this->message();
// 			  
			  // if(!isset($msg))
			  	 // return;
			  // //定义一个数据来存放日志信息
			  // $arr = array('create_date'=>$date,'username'=>$username,'action'=>$msg,'data'=>$data);
// 			  
// 			  
			  // $model = Model::factory('adminlog');
			   // $model->add($arr);
// 			  
// 			  
			  // //根据日期来定义文件夹
			// /*
			 // $dir1 ="/opt/data/www/kohana/application/admin/adminlog/".date('Ym',time())."/";//月份
			  // if(!file_exists($dir1))
			  	// mkdir($dir1);
			 // $file = $dir1.date('Ymd',time()).'.txt';
// 			
// 					
			 // //执行写文件操作
			 // if (!$handle = fopen($file, 'a')) {
         			// return;
    		  // }
// 
//     
			 // if (fwrite($handle, $str) === FALSE) {
			        // return;
			 // }
// 
// 		    
// 		
		    // fclose($handle);
// 			  
// 			  
			// */
// 			
	// }
	

}