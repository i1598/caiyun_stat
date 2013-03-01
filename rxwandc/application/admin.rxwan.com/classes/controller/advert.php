<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Advert extends Controller_Base{

	private $keys;
	private $model;
	private $type;
	private $act;

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->act = $this->request->param( "act" );
		$this->type = $this->request->param( "type" );
		$model_type = "advert_".$this->type;
		if($this->type!="advertlabel" && $this->type!="adverttype")
			$this->model = Model::factory($model_type);	
	}

	public function action_entrance(){
		$jsonData = $this->_param1();
		$jsonData=$jsonData['data'];
		//根据Type类型来实例化对应的模型
		$fun = $this->type."_".$this->act;
		if(!empty($jsonData)){
			$this->$fun($jsonData);	
		}else{
			$this->$fun();
		}
		
	}
	//广告位置
	private function advertzone_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array(  );
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}

	private function advertzone_add( $data ){
		$data = array_filter($data,array($this,'_filter'));	
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

	private function advertzone_edit( $data ){
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
	
	//广告位置的删除操作
	private function advertzone_del($data){
		$id = Arr::get( $data, "id", $id );
		$data = array('deleted'=>1);
		$row = $this->model->del( $id, $data );
    	if(empty($row)){
    		return $this->_responseBody( false );
    	}else{
    		//删除广告位
    		$advertise_model = Model::factory('Advert_Advertise');//实例化广告模型
    		$advertPos_model = Model::factory('Advert_Advertposition');//实例化广告位置模型
    		//首先找到该广告位置下有几个对应的广告位
    		$conditions = array("deleted"=>0,"advert_zone_id"=>$id);
    		$position_list = $advertPos_model->getList($conditions);
			foreach($position_list as $k=>$v){
				$advertise_model->edit($v['id'],array('is_delete'=>1),'advert_position_id');
			}
			$advertPos_model->edit($id,$data,'advert_zone_id');//将依附于该广告位的广告全部删除				
    		return $this->_responseBody( true );
    	}
	}
	
	
	//广告位
	private function advertposition_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array(  );
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}

	private function advertposition_add( $data ){
		$data = array_filter($data,array($this,'_filter'));	
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;
		$data['dateline'] = TIME;//添加日期
		list($id,$row) = $this->model->add( $data );
		if(empty($row)){
			
			return $this->_response( false );
		}else{
			$this->_data = $this->model->getDetail( $id );
			return $this->_response( true );	
		}
		
	}

	private function advertposition_edit( $data ){
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
	
	
	//删除广告位,与此同时删除依附于该广告位的所有广告
	private function advertposition_del($data){
    	$id = Arr::get( $data, "id", $id );
		if( isset( $data["id"] ) ) unset( $data["id"] );
		$data = array('deleted'=>1);
		$row = $this->model->del( $id, $data );
    	if(empty($row)){
    		return $this->_responseBody( false );
    	}else{
    		//此时删除广告
    		$advertise_model = Model::factory('Advert_Advertise');//实例化广告模型
    		$data = array('is_delete'=>1);
    		$advertise_model->edit($id,$data,'advert_position_id');//将依附于该广告位的广告全部删除
    		return $this->_responseBody( true );
    	}
	}
	
	//广告信息
	private function advertise_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$advert_position_id = Arr::get($_REQUEST,'advert_position_id',NULL);
		$conditions = array('advert_position_id'=>$advert_position_id);
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}
	//广告列表xinxi
	private function advertise_alllists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array();
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}

	private function advertise_add(  ){
		$data = array();
		$data['info'] = Arr::get($_REQUEST,'info',NULL);
		$data['url'] = Arr::get($_REQUEST,'url',NULL);
		$data['start_time'] = Arr::get($_REQUEST,'start_time',NULL);
		$data['end_time'] = Arr::get($_REQUEST,'end_time',NULL);
		$data['type'] = Arr::get($_REQUEST,'type',NULL);
		$data['weight'] = Arr::get($_REQUEST,'weight',NULL);
		$data['advert_position_id'] = Arr::get($_REQUEST,'advert_position_id',NULL);
		$data = array_filter($data,array($this,'_filter'));	
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;
		$file = 'upload_file';
		$this->model->upload($file,$data);
		exit;
		list($id,$row) = $this->model->add( $data );
		if(empty($row)){
			
			return $this->_response( false );
		}else{
			$this->_data = $this->model->getDetail( $id );
			return $this->_response( true );	
		}
		
	}

	private function advertise_edit( $data ){
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
	
	private function advertise_del( $data ){
		// echo '<pre>';
		// var_dump($data);
		// echo '</pre>';
		// exit;
    	$id = Arr::get( $data, "id", $id );
		if( isset( $data["id"] ) ) unset( $data["id"] );
		$data = array('is_delete'=>1);
		$row = $this->model->del( $id, $data );
    	if(empty($row)){
    		return $this->_responseBody( false );
    	}else{
    		return $this->_responseBody( true );
    	}
    	
	}
	
	
	/**
	 * 
	 */
	 private function advertlabel_lists(){
	 	$arr = array(
			array('id'=>1,'name'=>'首页面'),
			array('id'=>2,'name'=>'子页面')
		);
		
		$result = array('success'=>true,'data'=>$arr);
		echo json_encode($result);
	 }
	 
	 /**
	 * 
	 */
	 private function adverttype_lists(){
	 	$arr = array(
			array('id'=>1,'name'=>'图片'),
			array('id'=>2,'name'=>'SWF')
		);
		
		$result = array('success'=>true,'data'=>$arr);
		echo json_encode($result);
	 }
	 /**
	  * 
	  */
	 //广告每日统计
	private function advertdata_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$advertid = Arr::get($_REQUEST,'advertId',NULL);
		$conditions = array(  );
		if(isset($advertid)){
			$conditions['advert_id'] = $advertid;
		}else{
			$conditions['advert_id'] = 1;
		}
		$orders = array('key'=>'dateline','sort'=>'desc');
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset , $orders );
		if(empty($this->_totalCount)){
			$this->_response(false);	
		}else{
			$this->_response( true);	
		}
		
	}
	
	//
	//广告图表统计
	private function advertchat_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$advertid = Arr::get($_REQUEST,'advertId',NULL);
		$dateline = Arr::get($_REQUEST,'dateline',NULL);
		$conditions = array(  );
		if(isset($advertid)){
			$conditions['advert_id'] = $advertid;
		}else{
			$conditions['advert_id'] = 196;
		}
		
		if(isset($dateline)){
			$conditions['dateline'] = $dateline;
		}else{
			$dateline = strtotime(date('Y-m-d',TIME-86400).' 00:00:00');
			$conditions['dateline'] = $dateline;
		}
		$this->_data = $this->model->_getData( $conditions );
		
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
	
}