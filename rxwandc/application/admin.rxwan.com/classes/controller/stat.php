<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Stat extends Controller_Base{

	private $keys;
	private $model;
	private $type;
	private $act;

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->type = $this->request->param( "type" );
		$this->act = $this->request->param( "act" );
	}

	public function action_entrance(){
		$jsonData = $this->_param1();
		$jsonData=$jsonData['data'];
		//根据Type类型来实例化对应的模型
		if($this->type=='day' || $this->type=='coopstat'){
			$this->model = Model::factory('report_day');//默认是每日统计模式	
		}else{
			$this->model = Model::factory($this->type);
		}
		
		$action =$this->type.'_'.$this->act;
		$this->$action();
	}
	//每日统计列表
	private function day_lists(  ){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array(  );
		
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		
		$this->_response( true);
	}
	//针对合作商的统计
	private function coop_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$type = Arr::get($_REQUEST,'type',NULL);
		$softid = Arr::get($_REQUEST,'softid',NULL);
		$conditions = array();
		
		if(!empty($type)){
			$conditions['category_id'] = $type;
		}
		
		
		
		$this->_totalCount = $this->model->getCount( $conditions );
		$data = $this->model->getList( $conditions, $limit, $offset );//获取基本数据
		//接下来要将他们的数据进行统计，这里要注意的是进行合作商下的各个软件统计
		$soft_model = Model::factory('soft_list');
		$soft_lists = $soft_model->getList(array());
		//默认将软件列表的第一个拿出来
		if(empty($softid)){
			$softid = $soft_lists[0]['soft_id'];	
		}
		
		
		$report_model = Model::factory('report_day');
		foreach($data as $k=>&$v){
			$v['total_install'] = $report_model->get_quantity('quantity_install',$v['id'],$softid);
			$v['total_uninstall'] = $report_model->get_quantity('quantity_uninstall',$v['id'],$softid);
			$v['total_active'] = $report_model->get_quantity('quantity_active',$v['id'],$softid);
		}
		$this->_data = $data;
		
		$this->_response(true);
	}
	
	
	private function coop_add(){
		$data = $this->_param1();
		$data = $data['data'];//获取添加的数据
		//加上添加日期
		$data = array_filter($data,array($this,'_filter'));	
		// echo '<pre>';
		// var_dump($data);
		// echo '</pre>';
		$data['dateline'] = TIME;
		$this->_operation($this->model,'create',$data);
	}
	
	//针对合作商类型的统计
	private function coopcate_lists(){
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array();
		$this->_totalCount = $this->model->getCount( $conditions );
		$this->_data = $this->model->getList( $conditions, $limit, $offset );
		$this->_response(true);
	}

	private function coopcate_add(){
		$data = $this->_param1();
		$data = $data['data'];//获取添加的数据
		//加上添加日期
		$data = array_filter($data,array($this,'_filter'));	
		// echo '<pre>';
		// var_dump($data);
		// echo '</pre>';
		$data['dateline'] = TIME;
		
		$this->_operation($this->model,'create',$data);
	}
	//针对合作商的对应统计
	private function coopstat_lists(){
		$coopid=Arr::get($_REQUEST,'coopId',NULL);
		$softid=Arr::get($_REQUEST,'softId',NULL);
		//根据coopid找到对应的统计数据
		$username = Arr::get( $_REQUEST, "query" );
		$limit = Arr::get( $_REQUEST, "limit", NULL );
		$offset = Arr::get( $_REQUEST, "start", NULL );
		$conditions = array('union_member_id'=>$coopid,'soft_id'=>$softid);
		$this->_totalCount = $this->model->getCount( $conditions );
		$data = $this->model->getList( $conditions, $limit, $offset );
		//实例化软件名模型
		$model_softlist = Model::factory('soft_list');
		foreach($data as $k=>&$v){
			$v['soft_name'] = $model_softlist->get_name($v['soft_id']);
		}
		$this->_data = $data;
		$this->_response(true);
	}
	
	
	protected function _message( $struct ){
    	return Kohana::message( $this->_file, $struct );
    }
	
}