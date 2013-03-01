<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Toolbar extends Controller_Base{

	private $model;
	private $list;
	private $top;
	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->model = Model::factory( "toolbar" );
		$this->list =  $this->model->getResourceId();
		$this->top = $this->model->getParentResource();
		
	}

	
	/**
	 * 获取管理远的的信息列表
	 *
	 */
	public function action_adminlist(){
		$list=$this->list;
		$adminlist=array();
		$json = array();
		if(!empty($list[38])){
			$adminlist[]=$list[38];
		}
		if(!empty($list[39])){
			$adminlist[]=$list[39];
		}
		
		if(!empty($list[41])){
			$adminlist[]=$list[41];
		}
		
		if(!empty($adminlist)){
			$json['success']='true';	
			foreach($adminlist as $k=>$v){
				$json['data'][]=$v;
				
			}
			
		}else{
			$json['success']='false';
		}
		
		echo json_encode($json);
	}
	
	
	/**
	 * 获取统计数据的信息列表
	 *
	 */
	public function action_statlist(){
		$list=$this->list;
		$statlist=array();
		$json = array();
		if(!empty($list[43])){
			$statlist[]=$list[43];
		}
		if(!empty($list[46])){
			$statlist[]=$list[46];
		}
		if(!empty($list[47])){
			$statlist[]=$list[47];
		}
		
		if(!empty($statlist)){
			$json['success']='true';	
			foreach($statlist as $k=>$v){
				$json['data'][]=$v;
				
			}
			
		}else{
			$json['success']='false';
		}
		
		echo json_encode($json);
	}
	
	/**
	 * 获取版本管理的信息列表
	 *
	 */
	public function action_versionlist(){
		$list=$this->list;
		$versionlist=array();
		$json = array();
		if(!empty($list[44])){
			$versionlist[]=$list[44];
		}
		
		if(!empty($list[45])){
			$versionlist[]=$list[45];
		}
		
		if(!empty($list[48])){
			$versionlist[]=$list[48];
		}
		
		if(!empty($versionlist)){
			$json['success']='true';	
			foreach($versionlist as $k=>$v){
				$json['data'][]=$v;
				
			}
			
		}else{
			$json['success']='false';
		}
		
		echo json_encode($json);
	}
	
	/**
	 * 获取广告管理列表
	 * 
	 */
	public function action_advertlist(){
		$list=$this->list;
		$versionlist=array();
		$json = array();
		if(!empty($list[49])){
			$versionlist[]=$list[49];
		}
		
		if(!empty($list[50])){
			$versionlist[]=$list[50];
		}
		
		if(!empty($list[51])){
			$versionlist[]=$list[51];
		}
		
		if(!empty($list[52])){
			$versionlist[]=$list[52];
		}
		
		if(!empty($versionlist)){
			$json['success']='true';	
			foreach($versionlist as $k=>$v){
				$json['data'][]=$v;
				
			}
			
		}else{
			$json['success']='false';
		}
		
		echo json_encode($json);
	}
	
			/**
	 * 封装对想
	 */
	 public function action_getTop(){
	 	$json['success']='true';
		$json['data']=$this->top;	
	 	echo json_encode($json);
	 	//var_dump($this->top);
	 } 

}