<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Advert_Advertchat extends Model_DB {

    public function __construct( ) {
	        $config = array( "table"=>"advert_show_report_chat", "db"=>"reports" ,"key"=>"chat_day_id");
			parent::__construct( $config );
	}
	
	public function _getData($conditions){
		$data = $this->getDetail($conditions);
		 $result = array();
        if(empty($data)){
        	$data = $this->empty_result();
        	echo json_encode(array('success'=>true,'results'=>24,'data'=>$data));
			exit;
        }
            
        foreach($data as $key => $value){
            if(!substr_count($key, "quantity"))
                continue;
            $quantity = str_replace( "quantity_", "", $key);
            $arr["id"] = $quantity + 1;
            $arr["duration"] = $quantity + 1;
            $arr["flow"] = $value;
            $result[] = $arr;
        }
        echo json_encode(array('success'=>true,'results'=>count($result),'data'=>$result));
	}
	
	private function empty_result(){
		$data = array(
			array('id'=>1,'duration'=>1,'flow'=>0),
			array('id'=>2,'duration'=>2,'flow'=>0),
			array('id'=>3,'duration'=>3,'flow'=>0),
			array('id'=>4,'duration'=>4,'flow'=>0),
			array('id'=>5,'duration'=>5,'flow'=>0),
			array('id'=>6,'duration'=>6,'flow'=>0),
			array('id'=>7,'duration'=>7,'flow'=>0),
			array('id'=>8,'duration'=>8,'flow'=>0),
			array('id'=>9,'duration'=>9,'flow'=>0),
			array('id'=>10,'duration'=>10,'flow'=>0),
			array('id'=>11,'duration'=>11,'flow'=>0),
			array('id'=>12,'duration'=>12,'flow'=>0),
			array('id'=>13,'duration'=>13,'flow'=>0),
			array('id'=>14,'duration'=>14,'flow'=>0),
			array('id'=>15,'duration'=>15,'flow'=>0),
			array('id'=>16,'duration'=>16,'flow'=>0),
			array('id'=>17,'duration'=>17,'flow'=>0),
			array('id'=>18,'duration'=>18,'flow'=>0),
			array('id'=>19,'duration'=>19,'flow'=>0),
			array('id'=>20,'duration'=>20,'flow'=>0),
			array('id'=>21,'duration'=>21,'flow'=>0),
			array('id'=>22,'duration'=>22,'flow'=>0),
			array('id'=>23,'duration'=>23,'flow'=>0),
			array('id'=>24,'duration'=>24,'flow'=>0),
		);
		return $data;
		
	}
}