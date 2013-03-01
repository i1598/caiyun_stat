<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Adminlog extends Model_DB {

	public function __construct( $config = array() ) {
		$config = array( "table"=>"adminlog", "db"=>"opsys" );
		parent::__construct( $config );
	}



}