<?php
	class Model_Coop extends Model_DB{
		public function __construct( ) {
	        $config = array( "table"=>"member", "db"=>"alliance" );
			parent::__construct( $config );
		}
	}
