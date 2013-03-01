<?php
	class Controller_File extends Controller_Base{
		public function __construct(Request $request, Response $response) {
			parent::__construct($request, $response);
		}
		
		
		public function action_list(){
			$view = View::factory('file/upload');
			$this->response->body($view);
		}
		
		public function action_upload(){
			// echo '<pre>';
			// print_r($_FILES);
			// echo '</pre>';
			// move_uploaded_file($_FILES['file']['tmp_name'],'d:/wamp/www/php_test/1.jpg');
			$a = FS::upload(array('file1'=>'3'),array('size'=>419430400,'prefix_dir'=>'d:/wamp/www/php_test/','ext'=>array('zip','rar')));
			echo '<pre>';
			var_dump($a);
			echo '</pre>';
		}
		
	}
