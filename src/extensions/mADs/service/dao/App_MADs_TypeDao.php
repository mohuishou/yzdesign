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
class App_MADs_TypeDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_ads_type';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id',"name","open_type","type","click","created_time");

    /**
     * 获取总条数
     * @author mohuishou<1@lailin.xyz>
     * @return string
     */
    public function getCount(){
        $sql = $this->_bindSql('SELECT COUNT(*) AS count FROM %s', $this->getTable());
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->getValue(array());
    }

    public function getAll(){
        $sql = $this->_bindSql('SELECT * FROM %s  ORDER BY id desc', $this->getTable());
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }

    public function getList($limit, $offset ,$where=""){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE 1 %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
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