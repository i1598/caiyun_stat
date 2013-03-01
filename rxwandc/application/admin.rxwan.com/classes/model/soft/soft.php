<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Soft_Soft extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"soft", "db"=>"soft" );
			parent::__construct( $config );
	}

}
