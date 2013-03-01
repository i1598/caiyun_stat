<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Adminlog extends Controller_Base {
	private $model;
    public function __construct(Request $request, Response $response) {
        parent::__construct($request, $response);
		$this->model = Model::factory( "adminlog" );
    }

	/**
	 * 查询管理员操作日志
	 */
	public function action_list(){
		$conditions = array();
		 $query = Arr::get( $_REQUEST, "query" );
    	$limit = Arr::get( $_REQUEST, "limit" );
    	$offset = Arr::get( $_REQUEST, "offset" );
    	
		if(!is_null($query)){
			$conditions['username']=$query;
		}
		//var_dump($conditions);exit;
    	$this->_totalCount = $this->model->getCount( $conditions );
    	$this->_data = $this->model->getList( $conditions, $limit, $offset );
    	return $this->_response( true );
	}    
	
	

}