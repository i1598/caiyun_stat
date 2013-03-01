<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Reinstall extends Model_DB{

	public function __construct( $uuid='' ){
		$config = array();
		if( ! is_null( $uuid ) ){
			$table= $this->table( $uuid );
			$config = array( "table"=>$table, "db"=>"reinstall" );	
		}
		parent::__construct( $config );
		
	}

    public function table($uuid=''){
	    $numerator = hexdec(substr($uuid, 0, 3));
	    return 'reinstall_'.($numerator%256);

	}

	private function database($uuid=''){
	    $numerator = hexdec(substr($key, 4, 1));
	    $db_postfix = '_'.($numerator%16);
	    return 'install'.$db_postfix;
	}


    


	

}
    