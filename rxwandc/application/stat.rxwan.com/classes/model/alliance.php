<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Alliance extends Model_DB{

	public function __construct( $uuid=NULL ){
		$config = array();
		$config = array( "table"=>'member', "db"=>"alliance" );	
		if( ! is_null( $uuid ) ){
			$table= $this->table( $uuid );
			
		}
		parent::__construct( $config );
	}

    private function table($uuid=''){
	    $numerator = hexdec(substr($uuid, 0, 3));
	    
	    return 'firstopen_'.($numerator%256);

	}

	private function database($uuid=''){
	    $numerator = hexdec(substr($uuid, 4, 1));
	    $db_postfix = '_'.($numerator%16);
	    return 'firstopen'.$db_postfix;
	}


    


	

}
    