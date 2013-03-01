<?php
/**
 * 关于版本的控制器
 * @param action 有 version
 * @
 */

defined('SYSPATH') or die('No direct script access.');

class Controller_Version extends Controller_Base{

	private $keys;
	private $model;
	private $type;
	private $act;

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->act = $this->request->param( "act" );
	}

	public function action_entrance(){
		$jsonData = $this->_param1();
		$jsonData=$jsonData['data'];
		//根据Type类型来实例化对应的模型
		$f = $this->act;
		$this->$f();
	}

	private function lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array(  );
		
		$totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		
		$this->_responseBody( true, $totalCount, null );
	}

	private function detail( $data ){
		$id = Arr::get( $data, $key, $_REQUEST[$key]);
		if( $data["id"] ) unset( $data["id"] );
		$this->_data = $this->model->getDetail( $id );
		$this->_msg = "success";
		return $this->_responseBody( true );
	}

	private function member( $data ){
		$id = Arr::get( $_REQUEST, "id", NULL );
    	$id = Arr::get( $data, "id", $id );
		//var_dump($data);exit;
		if( isset( $data["id"] ) ) unset( $data["id"] );
		$this->_msg = $this->_message( $this->act . ".fail" );
		$data = array_filter($data,array($this,'_filter'));
		if(isset($data["password"]))
			$data["password"] = md5( $data["password"] );
		if( $this->act == "create" ){
			//首先判断表中是否有该用户名
			$count = $this->model->getCount(array('username'=>$data['username'],'deleted'=>0));
			if($count>0){
				return $this->_responseBody(false);
			}
			list( $id, $row ) = $this->model->add( $data );	
		}elseif ( $this->act == "edit" )
			$row = $this->model->edit( $id, $data );
		elseif ( $this->act == "del" )
			$row = $this->model->del( $id );
		elseif ( $this->act == "privilege" )
			$row = $this->privilege( $id, $data );
    	if(empty($row))
    		return $this->_responseBody( false );
    	$this->_msg = $this->_message( $this->act . ".success" );
    	$this->_data = $this->model->getDetail( $id );
    	return $this->_responseBody( true );
	}

	private function role( $data ){
		$data = array_filter($data,array($this,'_filter'));
		$status =  Arr::get( $_REQUEST, "assign", NULL );
		if( isset($data["resource_id"]) && !empty( $data["resource_id"] ) )
			$data["resource_id"] = json_encode( $data["resource_id"] );
		if($this->act=='edit'&&$status=='1'){
			$key = Arr::get( $_REQUEST, "id", NULL );
			$resource_id = Arr::get( $_REQUEST, "resource_id", NULL );
			$resource_id = '['.$resource_id.']';
			$data1 = array('resource_id'=>$resource_id);
			$row = $this->model->edit($key,$data1);
			if($row>0){
				echo json_encode(array('success'=>true));
				
			}else{
				echo json_encode(array('success'=>false));
			}
		}elseif($this->act == 'create'){
			//首先判断表中是否有该角色名
			$count = $this->model->getCount(array('name'=>$data['name'],'deleted'=>0));
			if($count>0){
				$this->_msg = $this->_message( $this->act . ".fail" );
				return $this->_responseBody(false);
			}else{
				$this->_operation( $this->model, $this->act, $data );
			}
		}else{
			
			$this->_operation( $this->model, $this->act, $data );
		}
	}

	private function resource( $data ){
		$this->_operation( $this->model, $this->act, $data );
	}

	private function privilege( $id, $data ){
		$data["privilege"] = json_encode($data["privilege"]);
		return $this->model->edit( $id, $data );
	}

	private function _keys( $key ){
		$data = array( 
			"member"=>array(
				"username","password","realname","email","tel","role"
				),
			"privilege"=>array( "id", "privilege" ),
			"role"  =>array( "id","name", "resource_id" ),
			"resource"=>array( "name" ),
			);
		return Arr::get( $data, $key, NULL );
	}
	
	
	protected function _message( $struct ){
    	return Kohana::message( $this->_file, $struct );
    }
	
}