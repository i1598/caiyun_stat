<?php
defined('SYSPATH')or die( 'No direct script access.' );

class Model_Soft_File2soft extends Model_DB {
	
	
	public function __construct( ) {
	        $config = array( "table"=>"file2soft", "db"=>"soft" );
			parent::__construct( $config );
	}
	
	
	/**
	 * 上传
	 */
	public function upload($_file,$data){
		$result = false;
		$filename = $_FILES[$_file]['name'];
		$size = $_FILES[$_file]['size'];
		$path = $this->get_path($data['version_id'],$data['version_typeon'],$filename);
		$upload = FS::upload(array("$_file"=>$path),array('size'=>52428800,'prefix_dir'=>PATH_VERSION,"ext"=>array("exe",'zip','rar')));
		if($upload->flag){
			$data['file_typeon'] = $this->get_filetype($filename);
			$data['dateline'] = TIME;
			$data['filesize'] = $size;
			$data['file_path'] = $path.'.'. $upload->details[$path]['ext'];
			$data['md5sum'] = md5_file(PATH_VERSION.$data['file_path']);
			$file2version_id = $this->get_id_by_path($data['file_path'],$data['version_typeon']);
			//print_r($file2version_id);exit;
			//echo json_encode(array('success'=>true,'data'=>$data));exit;			
			$result = empty($file2version_id) ? (bool)$this->add($data) : (bool)$this->edit($file2version_id, $data,'file2version_id');
			echo json_encode(array('success'=>true,'result'=>$result));
			//print_r($result);
			//$this->record_log('上传了文件：'.$filename);
			
		}else{
			echo json_encode(array('success'=>false));exit;
		}
		//return $result;
	}

	/**
	 * 根据文件路径获取ID
	 */
	public function get_id_by_path($file_path,$version_typeon){
		return DB::select('file2version_id')->from($this->_table)->where('is_delete','=',0)->and_where('version_typeon','=',$version_typeon)->and_where('file_path','=',$file_path)->limit(1)->order_by('dateline','DESC')->execute(Database::instance('soft'))->get('file2version_id');
	}
	
	
	/**
	 * 获取文件类型
	 */
	public function get_filetype($filename){
		$file_typeon = 0;
		if(stripos($filename,'setup') !== false){
			$file_typeon = 1;
		}elseif(stripos($filename,'partner') !== false){
			$file_typeon = 2;
		}elseif(stripos($filename,'silent') !== false){
			$file_typeon = 3;
		}
		return $file_typeon;
	}
	
	/**
	 * 获取上传路径
	 */
	public function get_path($version_id,$version_typeon,$filename){
		$path = 'beta';
		if($version_typeon == 1){
			$path = 'setup';
		}elseif($version_typeon == 2){
			$path = 'update';
		}
		$model_soft = Model::factory('soft_soft');
		$detail = $model_soft->getOne(array('id'=>$version_id));
		$path .= '/'.$detail['version_title'].'/'.basename($filename,'.exe');
		return $path;
	}
}
