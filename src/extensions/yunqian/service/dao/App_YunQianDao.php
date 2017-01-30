<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRC:library.base.PwBaseDao');

/**
 * App_YunQianLevelDao
 *
  * @author 芸烩工作室 <89652519@qq.com>
 * @copyright @2013-2113 yhcms.com
 * @license http://bbs.yhcms.com
 */

class App_YunQianDao extends PwBaseDao {
	
    protected $_table = 'app_yunqian';
    protected $_pk = 'id';
	protected $_dataStruct = array('id', 'uid', 'author', 'times', 'qdxq', 'money', 'ip', 'content');
	
 	 //**
  	public function _count(){
		$sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s', $this->getTable());
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->getValue(array());
	}
	
	public function countLei($where) {
		$sql = $this->_bindSql('SELECT qdxq, COUNT(*) AS count FROM %s  where id %s GROUP BY qdxq', $this->getTable(),$where);
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->queryAll(array(), 'qdxq');
	}
	
  	//**
  	public function countByWhere($where){
		$sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s WHERE id %s ', $this->getTable(), $where);
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->getValue(array());
	}
	
	public function getList($where, $limit, $offset){
		$sql = $this->_bindSql('SELECT * FROM %s WHERE id %s ORDER BY times asc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
		$smt = $this->getConnection()->createStatement($sql);
		return $smt->queryAll(array());
	}
	
	 //**
	public function getInfoById($id){
		return $this->_get($id);	
	}
	 
	//添加**
    public function add($data){
        return $this->_add($data);
    }
	
	/** 
	 * 删除数据**
	 *
	 * @param int $uid  用户ID
	 * @return int
	 */
	public function delete($id) {
		return $this->_delete($id);
	}
	
	/** 
	 * 根据用户ID更新数据**
	 *
	 * @param int $uid    说说ID
	 * @param array $fields  数据
	 * @return boolean|int
	 */
	public function update($id, $fields, $increaseFields = array(), $bitFields = array()) {
		return $this->_update($id, $fields, $increaseFields, $bitFields);
	}
	
}