<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<title><?php echo $title ?>_热血玩游戏_</title>
		<script type="text/javascript" src="http://lib.rxwan.com/swfobject.js"></script>
		<script type="text/javascript">
			if (swfobject.hasFlashPlayerVersion("6.0.0")) {
  // Overwrite regular CSS used for alternative content to enable Full Browser Flash
  swfobject.createCSS("html", "height:100%;");
  swfobject.createCSS("body", "margin:0; padding:0; overflow:hidden; height:100%;");
  swfobject.createCSS("#container", "height:100%;");
}
			swfobject.embedSWF("http://flash.2144.cn/qigongzhu/<?php echo $flash ?>", "gameid", "100%","100%","9.0.0","" )
		</script>
		<style>
			body {
				margin: 0px 0px 0px 0px;
			}
		</style>
	</head>
	<body>
		<div id="gameid" style="width: 100%; height: 100%;">

		</div>
	</body>
</html>