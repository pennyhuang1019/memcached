<?php
header("content-Type:text/html;charset=utf8");
/**
* 统计各个节点的命中率
*/
require('config.php');
$mem = new memcache();
$gets = 0;
$hits = 0;
foreach ($memserv as $k => $v) {
	$mem->connect($v['host'],$v['port'],2);

	$ret = $mem->getstats();
	$gets += $ret['cmd_get'];
	$hits += $ret['get_hits'];

	// echo $k,'号服务器命中率是：';
	// print_r($mem->getstats());
	// echo '<br/>';
} 

$rate = 1;
if($gets >0){
	$rate = $hits / $gets;
}

echo $rate;



?>