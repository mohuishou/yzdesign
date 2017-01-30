<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('LIB:base.PwBaseDm');

/**
 * 信息数据模型
 *
 * @package forum
 */

class PwQianinfoDm extends PwBaseDm {
	
	public $id;

	public function __construct($id = 1) {
		$this->id = $id;
	}

	public function setNewmember($newmember) {
		$this->_data['newmember'] = $newmember;
		return $this;
	}
	//签到之星 **
	public function setnewUid($v) {
		$this->_data['newuid'] = intval($v);
		return $this;
	}
	
	public function setTotal($totalmember) {
		$this->_data['total'] = intval($totalmember);
		return $this;
	}

	//总签到 **
	public function addTotal($num) {
		$this->_increaseData['total'] = intval($num);
		return $this;
	}

	public function setHigholnum($higholnum) {
		$this->_data['higholnum'] = intval($higholnum);
		return $this;
	}

	public function setHigholtime($higholtime) {
		$this->_data['higholtime'] = intval($higholtime);
		return $this;
	}

	public function setDtime($higholtime) {
		$this->_data['dtime'] = intval($higholtime);
		return $this;
	}
	
	//昨天签到 **
	public function setZqian($yposts) {
		$this->_data['zqian'] = $yposts;
		return $this;
	}
	
	//今天签到 **
	public function setDqian($hposts) {
		$this->_data['dqian'] = $hposts;
		return $this;
	}

	//今天签到更新 **
	public function addDqian($num) {
		$this->_increaseData['dqian'] = intval($num);
		return $this;
	}
	
	//月统计签到 **
	public function setMqian($hposts) {
		$this->_data['mqian'] = $hposts;
		return $this;
	}
	
	//月统计签到更新 **
	public function addMqian($num) {
		$this->_increaseData['mqian'] = intval($num);
		return $this;
	}
	
	//月签到时间 **
	public function setMtime($num) {
		$this->_data['mtime'] = intval($num);
		return $this;
	}
	
	//今日签到帖 **
	public function setDtid($v) {
		$this->_data['dtid'] = intval($v);
		return $this;
	}
	
	//更新奖励 **
	public function addMoney($num) {
		$this->_increaseData['zmoney'] = intval($num);
		return $this;
	}
	
	protected function _beforeAdd() {
		return true;
	}

	protected function _beforeUpdate() {
		return true;
	}
}
?>