<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * collectionItemDao
 * 收藏管理
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class CollectionItemDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_model_picture';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id','bid','uid','mid','created_time','updated_time');
	
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