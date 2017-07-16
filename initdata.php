<?php
header("content-Type:text/html;charset=utf8");
/**
*为各memcache节点连接，并填充1000条数据
*
*步骤：引入配置文件，连接各个节点并写入数据
*/

set_time_limit(0);
require('config.php');
require('hash.php');

$diser = new $_dis();

//添加服务器节点
foreach ($memserv as $k => $v) {
	$diser->addNode($k);
}

$mem = new memcache();
for ($i=1; $i <=10000 ; $i++) {
	$i = sprintf('%04d',$i);
	$key = 'key'. $i;
	$value = 'value'.$i;
	$serv = $memserv[$diser->lookup($key)]; //计算该键所在的服务器节点
	$mem->pconnect($serv['host'],$serv['port'],2); //连接该服务器节点
	$mem->add($key,$value,0,0); //将该键和值写入该节点缓存
	usleep(3000);
}

echo 'full';





?>