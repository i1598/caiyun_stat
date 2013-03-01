<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Soft_List extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"soft_name", "db"=>"soft" );
			parent::__construct( $config );
	}
	
	public function get_name($id){
			$end = TIME;
			return DB::select ( 'soft_name' )->from ( $this->_table )->where('soft_id','=',$id)->execute ( Database::instance ( 'soft' ) )->get ( 'soft_name' );
		}
}