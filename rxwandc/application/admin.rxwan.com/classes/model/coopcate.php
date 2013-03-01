<?php
	class Model_Coopcate extends Model_DB{
		public function __construct( ) {
	        $config = array( "table"=>"category_member", "db"=>"alliance" );
			parent::__construct( $config );
		}
	}
