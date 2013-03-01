<?php
//session_start();
defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller_Base{

	private $model;
	

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
	}
	
	
	/**
	 * @获取对应广告位的广告信息
	 * @return $this->
	 */
	public function action_getad(){
		$ad1 = "http://img.rxwan.com/ad/skycn_ad/swfad/320x270-22.swf";
		$ad2 = "http://img.rxwan.com/ad/skycn_ad/swfad/320x270-26.swf";
		$ad3 = "http://img.rxwan.com/ad/skycn_ad/swfad/320x270-27.swf";
		$ad;
		$num = $_GET["num"];
		if($num == 1){
			$ad = $ad1;
		}else if($num == 2){
			$ad = $ad2;
		}else{
			$ad = $ad3;
		}
		header("Location: $ad");
		exit;
	}
	

	public function action_geturl(){
	    $url1 = "http://3054.mnwan.com/gg3054a/";
		$url2 = "http://3052.mnwan.com/gg3052a/";
		$url3 = "http://3053.mnwan.com/gg3053a/";
		
		$url;
		$num = $_GET["num"];
		if($num == 1){
			$url = $url1;
		}else if($num == 2){
			$url = $url2;
		}else{
			$url = $url3;
		}
		header("Location: $url");
		exit;
	}

}