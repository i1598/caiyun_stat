<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller {

    private $model;

    public function __construct(Request $request, Response $response) {
        parent::__construct($request, $response);
		//$this->model = Model::factory( "opsys_member" );
		//Session::instance();
    }

    public function action_autoPull(){
        $project = Arr::get($_REQUEST,'project','');
        $key = Arr::get($_REQUEST,'key','');
        if(empty($project) || empty($key))
            $this->request->redirect("",200);
        if($project == "fanli" && $key="lulugengjiankang"){
            echo system("cd /opt/data/www/caiyunfanli");
            echo system("git pull origin master");
        }
    }
 	
	
	/**
	 * 用户的登陆注册
	 */
	
	 public function action_index() {
	 	$name = "install";
	 	$config = Kohana::$config->load('database');
		var_dump($config);exit;
	 	$model = Model::factory('users');
		$data = $model->add(array('name'=>'lisi','age'=>44));
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
		//echo 1;
		exit;
		if(isset($_SESSION['username']) && ($_SESSION['token'])){
			$this->response->body($this->view->fetch('index/index.html'));
			
		}else{
			$this->response->body($this->view->fetch('index/login.html'));
		}
		
		
		 
		
		// $this->response->body($this->view->fetch('index/index.html'));
		
	 }
	
	public function action_shit(){
		$user = Session::instance()->get("username");
		// session_start();
		var_dump($user);
	}

	public function action_list(){
		$this->response->body($this->view->fetch('index/index.html'));
	}
	
	
}