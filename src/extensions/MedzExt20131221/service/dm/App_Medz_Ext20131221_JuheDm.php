<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * App_Medz_Ext20131221_JuheDm - 数据模型
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_Medz_Ext20131221_JuheDm extends PwBaseDm {
	protected $tid;
	
	/**
	 * @return field_type
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setTid($tid) {
		$this->id = $tid;
		$this->_data['tid'] = $tid;
		return $this;
	}
	
	/**
	 * set table field value
	 *
	 * @param mixed $field_value
	 */
	public function setType($type) {
		$this->_data['type'] = $type;
	}
	
	public function setTids($tids) {
		$this->_data['tids'] = $tids;
	}

	/* (non-PHPdoc)
	 * @see PwBaseDm::_beforeAdd()
	 */
	protected function _beforeAdd() {
		// TODO Auto-generated method stub
		//check the fields value before add
		return true;
	}

	/* (non-PHPdoc)
	 * @see PwBaseDm::_beforeUpdate()
	 */
	 protected function _beforeUpdate() {
		// TODO Auto-generated method stub
	 	//check the fields value before update
		return true;
	}
}

?>