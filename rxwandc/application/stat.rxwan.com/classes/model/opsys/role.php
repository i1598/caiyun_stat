<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Opsys_Role extends Model_DB {

	public function __construct( $config = array() ) {
		$config = array( "table"=>"role", "db"=>"opsys" );
		parent::__construct( $config );
	}



}