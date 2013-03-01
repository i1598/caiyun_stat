<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Install extends Model_DB{
	// protected $_table_names_plural = false;
	// protected $_db_group;
	// protected $_table_name;
	// protected $_primary_key = 'uuid' ;
	public function __construct( $uuid='' ){
		$config = array();
		if( ! is_null( $uuid ) ){
			$table= $this->table( $uuid );
			$config = array( "table"=>$table, "db"=>"install");	
		}
		parent::__construct( $config );
		
		// if( ! is_null( $uuid ) ){
			// $table= $this->table( $uuid );
		// }
		 // $this->_table_name = $table;
		 // $this->_db_group  = "install";	 
		 // parent::__construct(  );
	}

    public function table($uuid=''){
	    $numerator = hexdec(substr($uuid, 0, 3));
	    return 'install_'.($numerator%256);

	}

	private function database($uuid=''){
	    $numerator = hexdec(substr($key, 4, 1));
	    $db_postfix = '_'.($numerator%16);
	    return 'install'.$db_postfix;
	}


    


	

}
    