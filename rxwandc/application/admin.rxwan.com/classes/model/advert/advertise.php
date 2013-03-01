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
		$upload = FS::upload(array("$_file"=>$path),array('size'=>52428800,'prefix_dir'=>PATH_ADVERT,"ext"=>array("jpg",'gif','png')));
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
}