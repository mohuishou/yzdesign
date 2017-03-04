<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * typeDao
 * 主要用于模型类别管理
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class TypeDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_model_type';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id','name','style','version','img_type','light','created_time','updated_time');

    public function getList($where=1, $limit=10, $offset=0){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE  %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }
	
	public function add($fields) {
		$fields['created_time']=time();
		$fields['updated_time']=time();
		return $this->_add($fields, true);
	}
	
	public function update($id, $fields) {
        $fields['updated_time']=time();
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