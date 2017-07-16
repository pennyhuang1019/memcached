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

//取模算法实现
class Moder implements hash,distribution{
	protected $_nodes = array();//存放节点数组
	protected $cnt = 0; //节点数量
	
	//计算哈希值
	public function _hash($str){
		return sprintf('%u',crc32($str));
	}
	//添加服务器节点
	public function addNode($node){
		if(in_array($node, $this->_nodes)){
			return true;
		}
		$this->_nodes[] = $node;
		$this->cnt += 1;
		return true;
	}
	//删除服务器节点
	public function deleteNode($node){
		if(!in_array($node, $this->_nodes)){
			return true;
		}
		$key = array_search($node, $this->_nodes);
		unset($this->_nodes[$key]);
		$this->_nodes = array_merge($this->_nodes); //重新将数组的键由0开始排序
		$this->cnt -= 1;
		return true;
	}
	//查找某个键对应的服务器节点
	public function lookup($key){
		$num = $this->_hash($key) % $this->cnt;
		return $this->_nodes[$num];
	}

	public function getNodes(){
		print_r($this->_nodes);
	}
}

//一致性哈希算法实现
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

// $con = new Moder();//new Consistent();
// $con->addNode('a');
// $con->addNode('b');
// $con->addNode('c');

// echo '所有服务器如下：<br/>';
// $con->getNodes();
// echo '当前落点值是 ：<br/>';
// echo $con->_hash('name'),'<br/>';
// echo $con->lookup('name');

// echo '<hr/>';
// $con->deleteNode('a');
// echo '所有服务器如下：<br/>';
// $con->getNodes();
// echo '当前落点值是 ：<br/>';
// echo $con->_hash('name'),'<br/>';
// echo $con->lookup('name');





?>


