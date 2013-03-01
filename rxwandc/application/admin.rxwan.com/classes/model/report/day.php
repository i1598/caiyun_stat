<?php
	class Model_Report_Day extends Model_DB{
		public function __construct( ) {
	        $config = array( "table"=>"report_day", "db"=>"reports" );
			parent::__construct( $config );
		}
		
		public function get_quantity($field,$id,$softid,$start=0,$end=""){
			$end = TIME;
			return DB::select ( 
				DB::expr ( 'SUM(`'.$field.'`) AS total_quantity' ) )->from ( $this->_table )
						->where('union_member_id','=',$id)
						->and_where_open()
						->where('soft_id','=',$softid)
						->and_where ( 'dateline', '>=', $start )
						->and_where ( 'dateline', '<=', $end )
						->and_where_close()
						->execute ( Database::instance ( 'reports' ) )
						->get ( 'total_quantity' );
		}
	}
