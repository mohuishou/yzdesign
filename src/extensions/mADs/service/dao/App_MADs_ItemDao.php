<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * App_MADs_MADsDao - dao
 * 广告条目数据模型
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MADs_ItemDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_ads_item';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id',"title","link","imgpath",'type_id',"description","window_title","click","script","created_time");

    public function getList($where, $limit, $offset){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE  %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }

    public function countByWhere($where){
        $sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s WHERE 1 %s ', $this->getTable(), $where);
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->getValue(array());
    }

	public function add($fields) {
        isset($fields['click']) || $fields['click']=0;
        isset($fields['created_time']) || $fields['created_time']=time();
		return $this->_add($fields, true);
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