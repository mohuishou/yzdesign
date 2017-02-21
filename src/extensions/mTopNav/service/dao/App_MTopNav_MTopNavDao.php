<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * App_MTopNav_MTopNavDao - dao
 *
 * @author 莫回首 <>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MTopNav_MTopNavDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_topnav';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id' ,'title','link','style','a','created_time');
	
	public function add($fields) {
		isset($fields['created_time']) || $fields['created_time']=time();
		return $this->_add($fields, true);
	}

	public function getList($limit, $offset ,$where=1){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }
	
	public function update($id, $fields) {
		return $this->_update($id, $fields);
	}
	
	public function delete($id) {
		return $this->_delete($id);
	}
	
	public function get($id) {
		return $this->_get($id);
	}
}

?>