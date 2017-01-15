<?php
defined('WEKIT_VERSION') or exit(403);

class App_I361serDao extends PwBaseDao {
	
	protected $_table = 'app_i361ser';
	protected $_pk = 'id';
	protected $_dataStruct = array('id', 'order_no', 'price','prices','number','state','ctype', 'type','created_userid','created_time','ordernum','code','kahao','password','msg');
	
  	//**
  	public function countByWhere($where){
		$sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s WHERE 1 %s ', $this->getTable(), $where);
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->getValue(array());
	}
	
	public function getList($where, $limit, $offset){
		$sql = $this->_bindSql('SELECT * FROM %s WHERE 1 %s ORDER BY created_time desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->queryAll(array());
	}
	
	public function add($fields) {
		return $this->_add($fields, true);
	}
	
	public function delete($id) {
		return $this->_delete($id);
	}
	
	public function update($id, $fields) {
		return $this->_update($id, $fields);
	}

	public function get($id) {
		return $this->_get($id);
	}
	
	public function updateByOid($oid, $fields) {
		$sql = $this->_bindSql("UPDATE %s SET %s WHERE order_no=?", $this->getTable(), $this->sqlSingle($fields));
		return $this->getConnection()->createStatement($sql)->execute(array($oid));
	}
	
	public function deleteByOid($oid) {
		$sql = $this->_bindTable("DELETE FROM %s WHERE order_no=?");
		return $this->getConnection()->createStatement($sql)->execute(array($oid));
	}

	public function getByOid($oid) {
		$sql = $this->_bindSql('SELECT * FROM %s WHERE order_no=?', $this->getTable());
		return $this->getConnection()->createStatement($sql)->getOne(array($oid));
	}
}