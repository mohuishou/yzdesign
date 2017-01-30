<?php

defined('WEKIT_VERSION') || exit('Forbidden');


/**
 * PwYunQianUserDm
 *
  * @author 芸烩工作室 <89652519@qq.com>
 * @copyright @2013-2113 yhcms.com
 * @license http://bbs.yhcms.com
 */

/**

 */

class PwYunQianUserDm extends PwBaseDm {

	protected $_data = array();
	public $id;

	public function __construct($id = 1) {
		$this->id = $id;
	}
	
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

	public function setMoney($v){
		$this->_data['money'] = (int)$v;
		return $this;
	}
	
	//总奖励更新 **
	public function addMoney($num) {
		$this->_increaseData['money'] = intval($num);
		return $this;
	}

	//总签到 **
	public function addDay($num) {
		$this->_increaseData['day'] = intval($num);
		return $this;
	}
	
	public function setContent($v){
		$this->_data['content'] = $v;
		return $this;
	}
	
	public function setQdxq($v){
		$this->_data['qdxq'] = $v;
		return $this;
	}
	//月签到 **
	public function setMday($num) {
		$this->_data['mday'] = intval($num);
		return $this;
	}

	//月签到 **
	public function addMday($num) {
		$this->_increaseData['mday'] = intval($num);
		return $this;
	}
	
	//月签到时间 **
	public function setMtime($num) {
		$this->_data['mtime'] = intval($num);
		return $this;
	}
	
	public function setDj($v){
		$this->_data['dj'] = (int)$v;
		return $this;
	}
	
	//月签到时间 **
	public function setDtop($num) {
		$this->_data['dtop'] = intval($num);
		return $this;
	}
	
	public function _beforeAdd(){
		return true;

	}
	
	public function _beforeUpdate(){
		return true;

	}

}