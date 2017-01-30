<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 信息
 *
 * @package forum
 */

class Pw_YunQianUserInfoDao extends PwBaseDao {
	
	protected $_table = 'app_yunqian_qduser';
	protected $_pk = 'uid';
	protected $_dataStruct = array('uid', 'author', 'times', 'dtop', 'money', 'day', 'qdxq', 'content', 'dj','mtime', 'mday');
	
	public function get($id) {
		return $this->_get($id);
	}

	public function add($data) {
		return $this->_add($data);
	}
	
	public function update($id, $fields, $increaseFields = array()) {
		return $this->_update($id, $fields, $increaseFields);
	}
	
  	//**
  	public function countByWhere($where){
		$sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s WHERE uid %s ', $this->getTable(), $where);
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->getValue(array());
	}
	
	public function getList($where, $limit, $offset, $by='times asc'){
		$by = $by?$by:"times asc";
		$sql = $this->_bindSql('SELECT * FROM %s WHERE uid %s ORDER BY %s  %s', $this->getTable(), $where, $by, $this->sqlLimit($limit, $offset));
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->queryAll(array());
	}
}