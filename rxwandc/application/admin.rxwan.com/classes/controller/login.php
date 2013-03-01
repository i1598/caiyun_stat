<?php
//session_start();
defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller{

	private $model;
	private $_msg;
	private $_data;

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->model = Model::factory( "opsys_member" );
	}

	public function action_login(){
		
		// $jsonStr = file_get_contents("php://input");
		// $jsonStr = Arr::get( $_REQUEST, "json" );
		// $jsonObj = json_decode( $jsonStr["data"] );
		$username = Arr::get( $_REQUEST, "username", NULL );
		$password = Arr::get( $_REQUEST, "password", NULL );
		
		if( empty( $username )){
			$this->_msg = "login.data1";
			
			return $this->_response( true,1 );
		} 
		
		if( empty( $password )){
			$this->_msg = "login.data2";
			return $this->_response( true,2 );
		}
			
			
		$this->_msg = "login.fail";
		
		$condition = array( "username"=>$username );
		
		$detail = $this->model->getOne( $condition );
		//var_dump($detail);exit;
		if( empty( $detail ) || $detail["password"] != md5($password) )
			return $this->_response( true,3 );
		$data = array( 
			"dateline"=>TIME, 
			"username"=>Arr::get( $detail, "username" ),
			"password"=>Arr::get( $detail, "password" )
			 );	
		$token = $this->model->encrypt( $data ); 
		$this->model->setSession( "username", $username );
		$this->model->setSession( "token", $token );
		$this->_msg = "login.success";
		//$this->response->body($this->view->fetch('index/index.html'));
			
		return $this->_response( true,4 );
	}

	public function action_logout(){
		$this->model->delSession( "username" );
		$this->model->delSession( "token" );
		$this->_msg = "logout.success";
		return $this->_response( true );
	}

	public function action_isLogin(){
		$this->_msg = "login.data";
		$key = Arr::get( $_REQUEST, "key", NULL );
		$code = Kohana::$config->load( "code" );
		if( $key != $code["validLogin"] )
			return $this->_response( false );
		$username = $this->model->getSession( "username", NULL );
		$token = $this->model->getSession( "token", NULL );
		$this->_msg = "checkLogin.logged";
		if( is_null( $username ) && is_null( $token ) )
			return $this->_response( false );
		$this->_msg = "checkLogin.exists";
		$this->_data = array( "username"=>$username, "token"=>$token );
		return $this->_response( true );
	}
	
	private function _message(){
    	return Kohana::message( "common", $this->_msg );
    }

    private function _response( $success,$type='' ){
    	$json = array( "success"=>$success, "message"=>$this->_message(),'type'=>$type );
    	if( ! empty( $this->_data ) ) $json["data"] = $this->_data;
    	$this->response->body( json_encode( $json ) );
    }

}