<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Advert_Advertclick extends Model_DB {

    public function __construct( $ip=NULL ){
		$config = array();
		if( ! is_null( $ip ) ){
			$table= $this->table( $ip );
			$db = 'advert_click';
			$config = array( "table"=>$table, "db"=>$db );	
		}
		parent::__construct( $config );
	}

    private function table($ip=''){
	    $numerator = hexdec(substr($ip, 0, 3));
	    
	    return 'advert_click_'.($numerator%256);

	}

	
}