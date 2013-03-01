<?php
$mem = new Memcache;
$is_add = $mem->addServer(‘localhost‘, 12000, true, 1, 1, -1, false); // retrt_interval=-1, status=false
$is_set = $mem->set('key1', '中华人民共和国');
echo $mem->get('key1');
?>