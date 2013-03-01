<?php
defined( 'SYSPATH' )or die( 'No direct script access.' );

class Model_Upgrade_Version extends Model{
	
    private $table_version;
    private $table_file2version;
    
    public function __construct(){
        $this->table_version = 'soft';
        $this->table_file2version = 'file2soft';               
    }

    /**
     * 获取当前发布的版本
     * @param $typeon
     */
    public function get_version($soft_id,$typeon){
        $data = DB::select()->from($this->table_version)->where('is_delete','=',0)->and_where('soft_id','=',$soft_id)->and_where('typeon', '=', $typeon)->and_where('is_publish','=',1)->limit(1)->execute(Database::instance('soft'))->current();
    	//echo DB::select()->from($this->table_version)->where('is_delete','=',0)->and_where('soft_id','=',$soft_id)->and_where('typeon', '=', $typeon)->and_where('is_publish','=',1)->limit(1);
        //var_dump($data);
        if(!empty($data)){
    		$data = $this->get_file_info($data);
    	}
    	//var_dump($data);
        return $data;
    }

    /**
     * 获取文件信息
     * @param unknown_type $data
     */
    public function get_file_info($data,$file_typeon=3){
    	$file_info = DB::select()->from($this->table_file2version)->where('is_delete','=',0)->and_where('version_typeon','=',$data['typeon'])->and_where('version_id','=',$data['id'])->and_where('file_typeon','=',$file_typeon)->order_by('dateline','DESC')->limit(1)->execute(Database::instance('soft'))->current();    
    	if(!empty($file_info)){
            $data['file_path'] = $file_info['file_path'];
    		$data['md5sum'] = $file_info['md5sum'];
    		$data['bufsize'] = $this->Byte_Change($file_info['filesize']);
	    	$data['bugfixed']=$this->process_txt($data['bugfixed']);
	    	$data['features']=$this->process_txt($data['features']);
	    	$data['summary']=$this->process_txt($data['summary']);
    	}
    	return $data;    	
    }
	
    /**
     * 根据版本号获取信息
     * @param $version_name
     */
    public function get_version_by_title($soft_id,$version_title){    
    	$data = DB::select()->from($this->table_version)->where('is_delete','=',0)->and_where('version_title','=',$version_title)->and_where('soft_id','=',$soft_id)->limit(1)->execute(Database::instance('soft'))->current();
    	if(!empty($data)){
    		$data = $this->get_file_info($data);
    	}
        return $data;
    }

	/**
	 * 计算文件大小
	 */
	public function Byte_Change($size) {
		if ($size <= 1024) {
			$num = floor ( $size * 100 ) / 100;
			$ext = "K";
		} elseif ($size <= 1048576 and $size > 1024) {
			$num = floor ( ($size / 1024) * 100 ) / 100;
			$ext = "KB";
		} elseif ($size <= 1073741824 and $size > 1048576) {
			$num = floor ( ($size / 1048576) * 100 ) / 100;
			$ext = "MB";
		}
		return $num . " " . $ext;
	}   
    /**
     * 加工文本内容
     * @param $text
     */
    public function process_txt($text){
    	return str_replace(array("\n","\r\n"),'<br />',$text);
    }

    
    
}