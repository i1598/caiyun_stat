<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Opsys_Resource extends Model_DB {

	public function __construct( $config = array() ) {
		$config = array( "table"=>"resource", "db"=>"opsys" );
		parent::__construct( $config );
	}

	public function getResource( $ids ){
		$query = $this->query( array( "id"=>$ids ) );
		return $this->_exe( $query )
					->as_array();
	}

	public function getResourceById( $cateId ){
		$query = $this->query( array( "category_id"=>$cateId ) );
		return $this->_exe( $query )
					->as_array();
	}

	public function getTopResourceId(){
		$data = $this->getTopCategory();
		$ids = array();
		if( empty( $data ) ) return $ids;
		foreach( $data as $value )
			$ids[] = $value["id"];
		return $ids;
	}
	
	public function getFilterCategory($ids){
		$query = $this->query(array("deleted"=>0,"id"=>$ids));
		$query = $query->where("text","!=","");
		return $this->_exe( $query )
					->as_array();
	}

	public function getTopCategory(){
		$query = $this->query( array( "deleted"=>0, "category_id"=>0 ) );
		$query = $query->where("text","!=","");
		return $this->_exe( $query )
					->as_array();
	}


	public function getPanel( $cateId, array $permission ){
		$data = $this->getResourceById( $cateId );
		if( empty( $data ) ) return $data;
		$resources  = array();
		foreach ($data as $key => $value) {
			if( ! in_array( $value["id"], $permission ) )
				continue;	
			$resource = array();
			$resource["text"] = $value["text"];
			$resource["leaf"] = (bool) $value["leaf"];
			$resource["iconCls"] = $value["icon_cls"];
			$resource["widget"] = $value["widget"];
			$resources[] = $resource;
		}
		return $resources;
	}

}