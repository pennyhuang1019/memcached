<?php


header("content-Type:text/html;charset=utf8");

/**
*一致性哈希php实现
*/

//需要一个将字符串转化为正数的功能
interface hash{
	public function _hash($str);
}

//读取某个键的值
interface distribution{
	public function lookup($key);
}

//实现
class Consistent implements hash,distribution{
	protected $_nodes = array();
	protected $mul = 64;
	//把字符串转换成32位无符号的整型
	public function _hash($str){
		return sprintf('%u',crc32($str));
	}

	//核心功能，返回该节点所在的服务器
	public function lookup($key){
		$point = $this->_hash($key);
		$node = current($this->_nodes);//假设存在这个最小的服务器节点上
		foreach ($this->_nodes as $k=>$v) {
			if($point <= $k){
				$node = $v;
				break;
			}
		}
		return $node;

	}

	//添加服务器节点
	public function addNode($node){
		for ($i=0; $i < $this->mul; $i++) { 
			$pos = $node.'-'.$i;
			$this->_nodes[$this->_hash($pos)] = $node;
		}
		//服务器位置排序
		$this->_sortNode();
	}

	//删除服务器节点
	public function deleteNode($node){
		foreach ($this->_nodes as $k => $v) {
			if($v==$node){
				unset($this->_nodes[$k]);
			}
		}
	}

	public function _sortNode(){
		ksort($this->_nodes,SORT_REGULAR);
	}

	public function getNodes(){
		print_r($this->_nodes);
	}


}

$con = new Consistent();
$con->addNode('a');
$con->addNode('b');
$con->addNode('c');

echo '所有服务器如下：<br/>';
$con->getNodes();
echo '当前落点值是 ：<br/>';
echo $con->_hash('name'),'<br/>';
echo $con->lookup('name');

echo '<hr/>';
$con->deleteNode('a');
echo '所有服务器如下：<br/>';
$con->getNodes();
echo '当前落点值是 ：<br/>';
echo $con->_hash('name'),'<br/>';
echo $con->lookup('name');





?>


