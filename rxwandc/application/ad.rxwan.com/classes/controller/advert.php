<?php
//session_start();
defined('SYSPATH') or die('No direct script access.');

class Controller_Advert extends Controller_Base{

	private $model;
	

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
		$this->model = Model::factory('Advert_Advertise');
	}
	
	
	/**
	 * @获取对应广告位的广告信息
	 * @return $this->
	 */
	public function action_getad(){
		$label = Arr::get($_REQUEST,'label',NULL);
		//定义搜索条件
		$conditions = array();
		if(!empty($label)){
			$conditions['label'] = $label;	
		}
		$position_model = Model::factory('Advert_Advertposition');//广告位模型
		$position = $position_model->getDetail($conditions); //制定的广告位信息
		$advert_position_id  = $position['id']; //获取广告位置id
		//定义搜索条件
		$conditions = array('advert_position_id'=>$advert_position_id);
		$ad_list = $this->model->_getList($conditions);
		
		$ad = $this->rand_ad($ad_list);
		//改变广告的跳转地址
		$ad['url'] = "http://ad.rxwan.com/advert/go?advert_id=".$ad['id']."&url=".$ad['url'];
		
		//进行统计
		//与此同时进行一次统计
		$result = $this->statByShow($ad['id']);
		if($result === 0){
			//插入失败，记录到日志
		}
		echo json_encode($ad);
	}
	

	/**
	 * 加权随机
	 */
	 private function rand_ad($data){
	 	  $weight = 0;
		  $tempdata = array();
		  foreach ($data as $one) {
		  
			  $weight += $one['weight'];//将每个的权重叠加
			  
			  for ($i = 0; $i < $one['weight']; $i ++) {
			  $tempdata[] = $one; //与此同时在内部循环中嵌套,并根据权重将数据放到池子里,权重越高的，被重复放入池子的次数就高
			  
			 }
		 
		 }
		 $use = rand(0, $weight-1);
		 $one = $tempdata[$use];
		 return $one;
	 }
	
	/**
	 * 进行跳转，并统计
	 */
	 public function action_go(){
	 	$url = Arr::get($_REQUEST,'url',NULL);
		$advert_id = Arr::get($_REQUEST,'advert_id',NULL);
		if(!empty($url) && !empty($advert_id)){
			//首先进行点击统计
			$result = $this->statByClick($advert_id);
			if($result === 0){
				//统计失败，记录到日志
				
			}
			$this->request->redirect($url);
		}
	 }
	
	/**
	 * 执行展现统计
	 */	
	 private function statByShow($advertId){
	 	//获取对方的referer以及ip
	 	$data = array();
	 	$data['ip'] = ip2long($_SERVER['REMOTE_ADDR']); //客户端IP
	 	if(isset($_SERVER['HTTP_REFERER'])){
	 		$data['referer'] = $_SERVER['HTTP_REFERER']; //中间引用地址	
	 	}
		$data['advert_id'] = $advertId;
		$data['dateline'] = TIME;
	 	$model = new Model_Advert_Advertshow($data['ip']);
	 	list($id,$row) = $model->add($data);
		return $row;
	 }
	 /**
	  * 执行点击统计
	  */
	  private function statByClick($advertId){
	  	//获取对方的referer以及ip
	 	$data = array();
	 	$data['ip'] = ip2long($_SERVER['REMOTE_ADDR']); //客户端IP
	 	if(isset($_SERVER['HTTP_REFERER'])){
	 		$data['referer'] = $_SERVER['HTTP_REFERER']; //中间引用地址	
	 	}
		$data['advert_id'] = $advertId;
		$data['dateline'] = TIME;
	 	$model = new Model_Advert_Advertclick($data['ip']);
	 	list($id,$row) = $model->add($data);
		return $row;
	  }


}