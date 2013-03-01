<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Toolbar extends Model_DB {

    private $model;

	public function __construct( ) {
        $config = array("table"=>"role","db"=>"opsys");
        parent::__construct( $config );
        $this->model = Model::factory( 'opsys_member', $config );
	}

	public function getResourceId(){
		$username=$this->model->getSession('username');//获取用户名
		$user_detail =$this->model->getOne(array('username'=>$username));//获取当前用户的信息
		$roleid = $user_detail['role'];
		$model1 = Model::factory('opsys_role');
		$role_detail = $model1->getOne(array('id'=>$roleid));
		$resource_id= $role_detail['resource_id'];
		$resource_list = json_decode($resource_id,true);
		//print_r($resource_list);exit;
		$model2 = Model::factory('opsys_resource');
		$list = $model2->getList(array('id'=>array('in',$resource_list)));
		
		$list1 = array();
		$list2 = array();
		foreach($list as $key=>$value){
			foreach($value as $key1=>$value1){
				if($key1=='leaf'){
					$value2 = ($value1==1)?'true':'false';	
					$list1[$key1]=$value2;
				}
				
				
				
				if($key1 == 'icon_cls'){
					$list1['iconCls']=$value1;
				}
				
				if($key1 == 'widget' || $key1== 'text'){
					$list1[$key1]=$value1;
					
				}
				if($key1 == 'category_id'){
					$list1[$key1]=$value1;
				}
				
			}
			$list2[$value['id']]=$list1;
			
		}
		
		return $list2;
		
		//var_dump($list);
		//echo $role_detail['resource_id'];
		//$model1 = Model::factory('opsys_member',array("table"=>"resource","db"=>"opsys"));
		
	}

	//获取大类的项目
	function getParentResource(){
		$childs = $this->getResourceId();
		$arr = array();
		$ParentIds = array();
		foreach($childs as $k => $v){
				if(!empty($v['category_id'])){
					$ParentIds[]=$v['category_id'];
				}	
		}
		
		$new_ids=array_unique($ParentIds);
		//var_dump($new_ids);exit;
		$model = Model::factory('opsys_resource');
		$top  = $model->getFilterCategory(array('in',$new_ids));
		$toplist = array();
		foreach($top as $k=>$v){
			if(!empty($v['widget'])){
				$toplist[] = array('widget'=>$v['widget']);
			}
		}
		
		return $toplist;
	}
}
