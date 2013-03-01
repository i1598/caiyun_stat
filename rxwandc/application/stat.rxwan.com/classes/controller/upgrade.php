<?php
defined( 'SYSPATH' )or die( 'No direct script access.' );

class Controller_Upgrade extends Controller{
    public $model;
    public function __construct(Request $request,Response $response){
        parent::__construct($request,$response);
        $this->model = Model::factory('upgrade_upgrade');
       //  Utility::no_browser_cache();        
    }
    
	public function action_xml2() {
		$this->response->headers('Content-Type', 'text/xml');
		$output = '<?xml version="1.0" encoding="UTF-8"?>
<response>
    <current version="3.73.1.1" md5="2403e2c0722b0c51d9d6b3ed4ef7cd3f" releasetime="2011-10-25" urlauto="http://downcdn.caiyun.com/update/silent/caiyun_update_3.73_baidu.exe" urlmanual="http://downcdn.caiyun.com/update/partner/caiyun_update_3.73_baidu.exe" urltip="http://api.caiyun.com/upgrade/changes/3.5" bufsize=""/>
    <force condition="3.71" />
</response>
		';
		echo $output;
	}
	
	public function action_xml(){
		$data = $this->array_trim(array('version'=>Arr::get($_REQUEST,'version'),'pkgname'=>Arr::get($_REQUEST,'pkgname'),'soft_id'=>$this->request->param('soft_id')));
		$errors = $this->model->rule_gets($data);
		$output = '';
		if(empty($errors)){
			
			//获取当前版本信息
			$current_release = $this->model->get_release_current($data['soft_id']);
			
			//获得升级版本信息
            $current_update = $this->model->get_update_current($data['soft_id']);

            //var_dump($current_update['version_title']);
            //var_dump($current_update);
            //var_dump($current_release['file_path']);
            
			//$url_auto = "http://downcdn.caiyun.com/soft/".$current_update['file_path'];
            $url_auto = "";
            //$url_manual = $data["soft_id"]==1?"http://www.youhuaweishi.com/":"http://www.biansu7.cn/";
			$url_manual = "";
            $url_change = 'http://stat.rxwan.com/upgrade/changes/'.$data['soft_id'].'/'.$current_update['version_title'];
            $force = $this->model->get_force();
            $this->response->headers('Content-Type', 'text/xml');
            $output .= '<?xml version="1.0" encoding="UTF-8"?>
						<response>
						    <current version="'.$current_update['version_title'].'" md5="'.$current_update['md5sum'].'" releasetime="'.date('Y-m-d',$current_update['dateline']).'" urlauto="'.$url_auto.'" urlmanual="'.$url_manual.'" urltip="'.$url_change.'" bufsize="'.$current_update['bufsize'].'"/>
						    <force condition="'.$force.'" />
						</response>';		
		}else{
			foreach ($errors as $field=>$error){
	           $output .= "$field=>$error.\n";
	       }
		}
		echo $output;
	}
	

	public function action_changes(){
		$current = $this->model->get_changes($this->request->param('soft_id'),$this->request->param('version'));
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>升级日志提醒</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
body,dl,dd,dt{margin:0;padding:0;font-size:12px;}
dl{width:300px;font-size:9pt;line-height:1.5em;left:15px;padding-top:10px;} 
dt{font-size:16px;font-weight:bolder;} 
dd{padding-left:30px;}
</style>
</head>
<body>
<dl>
    <dt>新功能：</dt>
    <dd>'.$current['features'].'</dd>
</dl>
<dl>
    <dt>修复BUG：</dt>
    <dd>'.$current['bugfixed'].'</dd>
</dl>
<dl>
	<dt>优化体验：</dt>
	<dd>'.$current['summary'].'</dd>
</dl>
</body>
</html>';
	
	}
	
	
	/*
	 * 去掉空格
	 */
	private function array_trim($array){
		array_walk_recursive($array, create_function('&$item,&$key', '$item = trim($item);$key = trim($key);'));
        return $array;
	}
	
}
