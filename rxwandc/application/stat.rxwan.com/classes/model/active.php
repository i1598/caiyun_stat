<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Active extends Model_DB{

	public function __construct( $uuid=NULL ){
		$config = array();
		if( ! is_null( $uuid ) ){
			$table= $this->table( $uuid );
			$db = $this->database($uuid);
			$config = array( "table"=>$table, "db"=>$db );	
		}
		parent::__construct( $config );
	}

    private function table($uuid=''){
	    $numerator = hexdec(substr($uuid, 0, 3));
	    
	    return 'active_'.($numerator%256);

	}

	private function database($uuid=''){
	    $numerator = hexdec(substr($uuid, 4, 1));
	    $db_postfix = '_'.($numerator%16);
	    return 'active'.$db_postfix;
	}


    


	

}
    