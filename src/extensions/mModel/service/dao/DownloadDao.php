<?php
defined('WEKIT_VERSION') or exit(403);
/**
* pictureDao
* 图片上传管理
*
* @author 莫回首 <1@lailin.xyz>
* @copyright http://lailin.xyz
* @license http://lailin.xyz
*/
class DownloadDao extends PwBaseDao {
    
    /**
    * table name
    */
    protected $_table = 'app_m_model_download';
    /**
    * primary key
    */
    protected $_pk = 'id';
    /**
    * table fields
    */
    protected $_dataStruct = array('id','mid','uid','tid','created_time','updated_time');
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
    
    //获取用户的最新下载记录
    public function getByLast($mid,$uid){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE mid=%s AND uid=%s  ORDER BY id desc  %s', $this->getTable(), $mid,$uid, $this->sqlLimit(1, 0));
		$smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }
    
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