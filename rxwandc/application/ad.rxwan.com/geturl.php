<?php
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
	
?>