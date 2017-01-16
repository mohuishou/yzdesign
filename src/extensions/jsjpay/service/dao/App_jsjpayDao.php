<?php
defined('WEKIT_VERSION') or exit(403);

class App_jsjpayDao extends PwBaseDao {
	
	protected $_table = 'app_jsjpay';
	protected $_pk = 'id';
	protected $_dataStruct = array('id', 'uid', 'total','addnum','op','time');
	public function countByWhere($where){
		$sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s WHERE 1 %s ', $this->getTable(), $where);
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->getValue(array());
	}
	
	public function getList($where, $limit, $offset){
		$sql = $this->_bindSql('SELECT * FROM %s WHERE 1 %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->queryAll(array());
	}
	
	public function add($data) {
		return $this->_add($data, true);
	}
	
	public function delete($id) {
		return $this->_delete($id);
	}
	
	public function update($id, $data) {
		return $this->_update($id, $data);
	}
	
	public function deleteByOid($oid) {
		$sql = $this->_bindTable("DELETE FROM %s WHERE order_no=?");
		return $this->getConnection()->createStatement($sql)->execute(array($oid));
	}

	public function getsaddnum($addnum) {
		$sql = $this->_bindSql('SELECT * FROM %s WHERE addnum=? ORDER BY id desc', $this->getTable());
		return $this->getConnection()->createStatement($sql)->getOne(array($addnum));
	}
}