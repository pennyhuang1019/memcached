<?php

require('config.php');

$mem = new memcache(); //创建操作的客户端

$diser = new $_dis(); //实例化分布算法

//循环添加服务器节点
foreach ($memserv as $k => $v) {
	$diser->addNode($k);
}

//模拟减少一个服务器节点
$diser->deleteNode('D');

for ($i=0; $i <= 100000; $i++) {
	$i = sprintf('%04d',$i%10000);
	$key = 'key'.$i;
	$serv = $memserv[$diser->lookup($key)];  //找到$i所在的服务器节点
	$mem->connect($serv['host'],$serv['port'],2);
	//要是服务器没有找到，就添加到缓存
	if(!$mem->get($key)){
		$mem->add($key,'value'.$i,0,0);
	}

	$mem->close();//要关闭连接，因为脚本运行时间过长

	usleep(3000);

}










?>