<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Advert_Advertise extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"advertise", "db"=>"advert" );
			parent::__construct( $config );
	}
	
	
	/**
	 * 上传
	 */
	public function upload($_file,$data){
		$result = false;
		$filename = $_FILES[$_file]['name'];
		$size = $_FILES[$_file]['size'];
		$path = date('Y',TIME)."/".date('m',TIME)."/".date('d',TIME)."/".TIME."_".rand(0,1000);
		$upload = FS::upload(array("$_file"=>$path),array('size'=>52428800,'prefix_dir'=>PATH_VERSION,"ext"=>array("jpg",'gif','png')));
		if($upload->flag){
			$data['path'] = $path.'.'. $upload->details[$path]['ext'];
			$result = (bool)$this->add($data) ;
			echo json_encode(array('success'=>true,'result'=>$result));
			//print_r($result);
			//$this->record_log('上传了文件：'.$filename);
			
		}else{
			echo json_encode(array('success'=>false));exit;
		}
		//return $result;
	}
	
	/**
	 * 获取图片地址和跳转地址
	 */
	 public function _getList( $conditions, $limit=NULL, $offset=NULL, $orders=array() ){
		$query = $this->_query( $conditions );
		
		if( !empty( $orders ) )
			$query->order_by( $orders["key"], $orders["sort"] );
		if(!is_null($limit) && !is_null($offset))
			$query = $query->limit( $limit )->offset( $offset );
		return $this->_exe( $query )->as_array();
	}
	 /**
	  * 查询语句
	  */
	  public function _query( $conditions){
		$query = DB::select('id','info','path','start_time','end_time','weight','url')->from( $this->_table );
		if( empty( $conditions ) ) return $query;
		foreach( $conditions as $key=>$value ){
			
			if( is_array( $value ) )
				list( $co, $param ) = $value;
			else
				$param = $value;
			if( ! isset( $co ) ) $co = "=";
			$query->where( $key, $co, $param );
			unset( $co );
		}
		
		
		return $query;
	}
}