<?php

defined('SYSPATH') or die('No direct script access.');

class Model_DB extends Model {

	protected $_db;
	protected $_table;
	protected $_key;

	public function __construct( $config = array( ) ){
		$this->_db    = Arr::get( $config, "db", "default" );
		$this->_table = Arr::get( $config, "table", "" );
		$this->_key   = Arr::get( $config, "key", "uuid" );
	}

	public function add( $param ){
		$query = DB::insert( $this->_table, array_keys( $param ) )
				 ->values( $param );
		return $this->_exe( $query );
	}

	public function edit( $key,$param,$extra="" ){
		if(!empty($extra)){
			$this->_key = $extra;
		}	
		$query = DB::update( $this->_table )
				 ->set( $param );
		if(is_array($key)){
			foreach($key as $k=>$v){
				$query = $query->where($k,'=',$v);				
			}
			
		}else{
			$query = $query->where($this->_key,'=',$key);
		}	
		
		
		return $this->_exe( $query );
	}

	public function del( $key, $param=array("deleted"=>1) ){
		return $this->edit( $key, $param );
	}

	public function delete( $key ){
		$query = DB::delete( $this->_table )->where( $this->_key,"=", $key );
		return $this->_exe( $query );
	}

	public function query( $conditions){
		$query = DB::select()->from( $this->_table );
		if( empty( $conditions ) ) return $query;
		foreach( $conditions as $key=>$value ){
			
			if( is_array( $value ) )
				list( $co, $param ) = $value;
			else
				$param = $value;
			if( ! isset( $co ) ) $co = "=";
			$query->where( $key, $co, $param );
			unset( $co );
		}
		
		
		return $query;
	}

	public function getCount( $conditions, $distict = "" ){
		$distict = empty( $distict ) ? "*" : "DISTINCT '".$distict."' ";
		$query = $this->query( $conditions )
				 ->select( array(DB::expr("COUNT(".$distict.")"), 'totalCount'));
		return $this->_exe( $query )->get( "totalCount" );
	}
	
	
	public function getSum( $conditions, $field = "quantity" ){
		$query = $this->query( $conditions )
					->select(array(DB::expr("SUM(`". $field ."`)"),"total" ));
		return $this->_exe( $query )->get( "total" );
	}

	public function getList( $conditions, $limit=NULL, $offset=NULL, $orders=array() ){
		$query = $this->query( $conditions );
		
		if( !empty( $orders ) )
			$query->order_by( $orders["key"], $orders["sort"] );
		if(!is_null($limit) && !is_null($offset))
			$query = $query->limit( $limit )->offset( $offset );
		return $this->_exe( $query )->as_array();
	}
	
	

	public function getOne( $conditions ){
		$query = $this->query( $conditions );
		return $this->_exe( $query )->current();
	}

	public function getDetail( $key ){
		$condition = array( $this->_key=>$key );
		$query = $this->query( $condition );
		return $this->_exe( $query )->current();
	}

	protected function _exe( $query ){
		return $query->execute( Database::instance( $this->_db ) );
	}

	protected function fs( $key, $type="game", $filename=null){
		if(empty($_FILES[$key]['name']))
			return $filename;
		$upload_path = $type."/".date("m") . "/" . date("d") . "/";//文件的相对路径
		$path = PREFIX_UPLOAD_IMG .$upload_path;//文件的绝对路径 
		if(empty($filename))
			$filename = TIME . "_". Utility::rand(0,9999);
		$ext = array("jpg","jpeg","bmp","gif","png");
		$policy = array('prefix_dir' => $path, "ext"=>$ext);
		$upload = FS::upload( array( $key => $filename), $policy );
		if(!$upload->flag)
			return $upload;
		return PREFIX_COMMON_IMG.$upload_path.$filename . "." . $upload->details[$filename]["ext"];
	}

}