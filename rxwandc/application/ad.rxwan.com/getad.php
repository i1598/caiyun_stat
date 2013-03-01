<?php
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
?>