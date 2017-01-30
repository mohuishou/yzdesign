<?php
defined('WEKIT_VERSION') || exit('Forbidden');
/**
 * App_YunTao_InfoDs
 *
  * @author 芸烩工作室 <89652519@qq.com>
 * @copyright @2013-2113 yhcms.com
 * @license http://bbs.yhcms.com
 */

class App_YunQianLevel {
	 
 	//**
	public function count(){
		return $this->_getDao()->_count();
	}
	
	public function countByWhere($where){
		return $this->_getDao()->countByWhere($where);
	}
 
 	//根据说说ID获取信息**
 	public function getInfoById($id){
		return $this->_getDao()->getInfoById($id);
	}
 
	//**
	public function getListByWhere($where, $limit, $start){
		return $this->_getDao()->getList($where, $limit, $start);	
	}
	
    //添加**
    public function addLevel($title,$dj,$hits) {
        $fieldData = array('title'=>$title, 'dj'=>$dj, 'hits'=>$hits);
        return $this->_getDao()->add($fieldData);
    }

    //编辑**
    public function edit($title,$dj,$hits){
        $fieldData = array('title'=>$title, 'dj'=>$dj, 'hits'=>$hits);
        return $this->_getDao()->update($dj, $fieldData);
    }
	
    //删除**
    public function delete($id){
        return $this->_getDao()->delete($id);
    }

	 //还原等级记录
    public function toLevel($Leveldb) {
	    if($Leveldb){
			foreach($this->getListByWhere('', 0, 1000) as $k=>$v){
				$this->delete($v['dj']);
			}
			foreach($Leveldb as $key=>$val){
				$fieldData = array('title'=>$val[title], 'dj'=>$val[dj], 'hits'=>$val[hits]);
				$result = $this->_getDao()->add($fieldData);
			}
		}
		return $result;
    }	
   	protected function _getDao() {
        return Wekit::loadDao('SRC:extensions.yunqian.service.dao.App_YunQianLevelDao');
    }
 }