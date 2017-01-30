<?php

defined('WEKIT_VERSION') || exit('Forbidden');


/**
 * PwYunQianDm
 *
  * @author 芸烩工作室 <89652519@qq.com>
 * @copyright @2013-2113 yhcms.com
 * @license http://bbs.yhcms.com
 */

/**

 * 数据模型

 */

class PwYunQianDm extends PwBaseDm {

	protected $_data = array();

	public function setTime($v){
		$this->_data['times'] =(int) $v;
		return $this;
	}

	public function setAuthor($v){
		$this->_data['author'] = $v;
		return $this;
	}

	public function setUid($v){
		$this->_data['uid'] = (int)$v;
		return $this;
	}
	
	public function setQdxq($v){
		$this->_data['qdxq'] = (int)$v;
		return $this;
	}

	public function setMoney($v){
		$this->_data['money'] = (int)$v;
		return $this;
	}

	public function setIp($v){
		$this->_data['ip'] = $v;
		return $this;
	}

	public function setContent($v){
		$this->_data['content'] = $v;
		return $this;
	}
	
	public function _beforeAdd(){
		return true;
	}
	
	public function _beforeUpdate(){
		return true;
	}

}