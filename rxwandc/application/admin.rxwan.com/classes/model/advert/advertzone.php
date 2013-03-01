<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Advert_Advertzone extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"advert_zone", "db"=>"advert" );
			parent::__construct( $config );
	}
	
}