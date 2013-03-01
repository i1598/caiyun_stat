<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Advert_Advertdata extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"advert_report_day", "db"=>"reports" );
			parent::__construct( $config );
	}
	
}