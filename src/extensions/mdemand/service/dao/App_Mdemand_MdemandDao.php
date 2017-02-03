<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * App_Mdemand_MdemandDao - dao
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_Mdemand_MdemandDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'app_m_demand';
	/**
	 * primary key
	 */
	protected $_pk = 'id';
	/**
	 * table fields
	 */
	protected $_dataStruct = array('id',"name","phone","category","qq","detail","sex","design_type","design_name","status","created_time","updated_time");

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

    public function getNew($limit=20){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE status=1  ORDER BY id desc  %s', $this->getTable(), $this->sqlLimit($limit, 0));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }

	public function add($fields) {
        isset($fields['created_time']) || $fields['created_time']=time();
        isset($fields['updated_time']) || $fields['updated_time']=time();
        isset($fields['status']) || $fields['status']=0;
		return $this->_add($fields, true);
	}

    public function getList($limit, $offset ,$where=1){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE %s  ORDER BY id desc  %s', $this->getTable(), $where, $this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }
	
	public function update($id, $fields) {
        isset($fields['updated_time']) || $fields['updated_time']=time();
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