<html>
<head>
<meta charset="utf8" /> 
</head>
<body>
<p>
<?php
	$link = mysql_connect('localhost','root','soft798.com');
	//$link = mysql_connect('localhost','root','root');
	mysql_select_db('qynn');
	$sql = "select * from  dede2144_addon17  where aid=384";
	
	$res = mysql_query($sql,$link);
	//print_r($link);exit;
	$row = mysql_fetch_assoc($res);
	
	echo '<pre>';
	var_dump($row);
	echo '</pre>';
?>
</p>
</body>
</html>