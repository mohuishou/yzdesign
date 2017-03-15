<?php
defined('WEKIT_VERSION') or exit(403);
/**
* itemDao
* 模型管理
*
* @author 莫回首 <1@lailin.xyz>
* @copyright http://lailin.xyz
* @license http://lailin.xyz
*/
class ItemDao extends PwBaseDao {
    
    /**
    * table name
    */
    protected $_table = 'app_m_model_item';
    /**
    * primary key
    */
    protected $_pk = 'id';
    /**
    * table fields
    */
    protected $_dataStruct = array('id','cid','uid','tid','name','style','version','pictures','img_type','light','tags','price','file','description','liked','collection','status' ,'created_time','updated_time','look','download','liked');
    
    
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
    
    public function getList($where=1, $limit=10, $offset=0,$order="updated_time desc"){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE  %s  ORDER BY id desc , %s  %s', $this->getTable(), $where, $order,$this->sqlLimit($limit, $offset));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array());
    }
    
    //下载次数加1
    public function addDownload($id){
        $download=$this->get($id)['download']+1;
        return $this->_update($id,["download"=>$download]);
    }
    
    //点赞数目加1
    public function addLike($id){
        $liked=$this->get($id)['liked']+1;
        return $this->_update($id,["liked"=>$liked]);
    }
    
    //浏览数加1
    public function addLook($id){
		$data=$this->get($id);
        $look['look']=$data['look']+1;
        return $this->update($id,$look);
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