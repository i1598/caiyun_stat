<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Soft extends Controller_Base{

	private $keys;
	private $model;
	private $type;
	private $act;

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->act = $this->request->param( "act" );
		$this->model = Model::factory('soft_soft');	
	}

	public function action_entrance(){
		$jsonData = $this->_param1();
		$jsonData=$jsonData['data'];
		//根据Type类型来实例化对应的模型
		$act = $this->act;
		if(!empty($jsonData)){
			$this->$act($jsonData);	
		}else{
			$this->$act();
		}
		
	}

	private function lists(){
		$this->model->_join = 'soft_name';
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array(  );
		$soft_id = Arr::get($_REQUEST,'soft_id');
		$type = $this->request->param( "type" );
		if(!empty($type)){
			$conditions['typeon']=$type;
		}
		if(!empty($soft_id)){
			$conditions['soft_id']=$soft_id;
		}else{
			$conditions['soft_id'] = 1;
		}
		$this->_totalCount = $this->model->getJoinCount( $conditions );
		$orders = array('key'=>'dateline','sort'=>'desc');
		$this->_data = $this->model->getJoinList( $conditions, $limit, $offset,$orders );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}

	private function add( $data ){
		$data = array_filter($data,array($this,'_filter'));	
		$type = $this->request->param( "type" );
		$data['typeon']=$type;
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;
		list($id,$row) = $this->model->add( $data );
		if(empty($row)){
			
			return $this->_response( false );
		}else{
			$this->_data = $this->model->getDetail( $id );
			return $this->_response( true );	
		}
		
	}

	private function edit( $data ){
    	$id = Arr::get( $data, "id", $id );
		if( isset( $data["id"] ) ) unset( $data["id"] );
		$data = array_filter($data,array($this,'_filter'));
		$row = $this->model->edit( $id, $data );
    	if(empty($row)){
    		return $this->_responseBody( false );
    	}else{
    		return $this->_responseBody( true );
    	}
    	
	}

	/**
	 * 返回软件信息
	 * 
	 */
	private function show(){
		$this->model=Model::factory('soft_list');
		$this->_totalCount=$this->model->getCount(array());
		$this->_data = $this->model->getList(array());
		return $this->_response(true);		
	}	
	

	private function softnameadd($data){
		$this->model=Model::factory('soft_list');
		$data = array_filter($data,array($this,'_filter'));	
		$data['dateline'] = TIME;
		list($id,$row) = $this->model->add( $data );
		if(empty($row)){
			
			return $this->_response( false );
		}else{
			$this->_data = $this->model->getDetail( $id,"soft_id" );
			return $this->_response( true );	
		}
	}
	/**
	 * 上传
	 */
	private function upload(){
		
		$data = array(
			'version_id'=>Arr::get($_REQUEST,'version_id'),
			'version_typeon'=>Arr::get($_REQUEST,'typeon')
		);
		
		$model_file = Model::factory('soft_file2soft');
		$result = $model_file->upload('upload_file',$data);
		//$this->response->body($result);
	}
	
	/*
	 * 软件列表
	 * 
	 */
	 private function softlist(){
	 	$version_id = Arr::get($_REQUEST,'version_id');
		$typeon = Arr::get($_REQUEST,'typeon');
	 	$this->model = Model::factory('soft_file2soft');
		$conditions = array('version_id'=>$version_id,'version_typeon'=>$typeon);
		$this->_totalCount = $this->model->getCount($conditions);
		$data = $this->model->getList($conditions);
		
		foreach($data as $k=>$v){
			if(!empty($v)){
				$data[$k]['filename'] = basename($v['file_path']);
				$data[$k]['size'] = $this->Byte_Change($v['filesize']);
			}
		}
		$this->_data = $data;
		
		return $this->_response(true);
	 }
	 
	 /**
	 * 计算文件大小
	 */
	private function Byte_Change($size) {
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
}