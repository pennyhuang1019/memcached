<?php

//配置文件，配置memcached的节点信息
$memserv = array();

$memserv['A'] = array('host'=>'127.0.0.1','port'=>11211);
$memserv['B'] = array('host'=>'127.0.0.1','port'=>11212);
$memserv['C'] = array('host'=>'127.0.0.1','port'=>11213);
$memserv['D'] = array('host'=>'127.0.0.1','port'=>11214);
$memserv['E'] = array('host'=>'127.0.0.1','port'=>11215);

$_dis = 'Moder';//Consistent

?>