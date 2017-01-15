<?php
defined('WEKIT_VERSION') or exit(403);

class App_I361serDm extends PwBaseDm {

	protected $id = null;
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		$this->_data['id'] = $id;
		return $this;
	}

	public function setOid($oid) {
		$this->_data['oid'] = $oid;
		return $this;
	}

	public function setUid($uid) {
		$this->_data['created_userid'] = $uid;
		return $this;
	}

	public function setOrderno($val) {
		$this->_data['order_no'] = $val;
		return $this;
	}

	public function setPrice($val) {
		$this->_data['price'] = $val;
		return $this;
	}

	public function setPrices($val) {
		$this->_data['prices'] = $val;
		return $this;
	}

	public function setNumber($val) {
		$this->_data['number'] = $val;
		return $this;
	}

	public function setState($val) {
		$this->_data['state'] = $val;
		return $this;
	}

	public function setCtype($val) {
		$this->_data['ctype'] = $val;
		return $this;
	}

	public function setType($val) {
		$this->_data['type'] = $val;
		return $this;
	}

	public function setTime($val) {
		$this->_data['created_time'] = (int)$val;
		return $this;
	}

	public function setOrdernum($val) {
		$this->_data['ordernum'] = $val;
		return $this;
	}
	
	public function setCode($val) {
		$this->_data['code'] = $val;
		return $this;
	}

	public function setPassword($val) {
		$this->_data['password'] = $val;
		return $this;
	}
	
	public function setKahao($val) {
		$this->_data['kahao'] = $val;
		return $this;
	}
	
	public function setMsg($val) {
		$this->_data['msg'] = $val;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see PwBaseDm::_beforeAdd()
	 */
	protected function _beforeAdd() {
		//if (0 >= intval($this->getField('created_userid'))) return new PwError('非法UID');
		return true;
	}

	/* (non-PHPdoc)
	 * @see PwBaseDm::_beforeUpdate()
	 */
	 protected function _beforeUpdate() {
	 	//if (0 >= intval($this->getField('created_userid'))) return new PwError('非法UID');
		return true;
	}
}