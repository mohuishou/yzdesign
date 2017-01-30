<?php
defined('WEKIT_VERSION') || exit('Forbidden');
/**
 * App_YunQian
 *
  * @author 芸烩工作室 <89652519@qq.com>
 * @copyright @2013-2113 yhcms.com
 * @license http://bbs.yhcms.com
 */

class App_YunQian{
	 
 	//**
	public function count(){
		return $this->_getDao()->_count();
	}
	
	public function countLei($where){
		return $this->_getDao()->countLei($where);
	}
 
	public function countByWhere($where){
		return $this->_getDao()->countByWhere($where);
	}
 
	//**
	public function getListByWhere($where, $limit, $start){
		return $this->_getDao()->getList($where, $limit, $start);	
	}
	
 	//根据说说ID获取信息**
 	public function getInfoById($id){
		return $this->_getDao()->getInfoById($id);
	}
	
 	//获取用户最后一次打卡**
 	public function getInfoByUid($uid){
		if(!$uid) return array();
		$_info=$this->_getDao()->getInfoByUid($uid);
		return $_info[0];
	}
	
   //添加 **
    public function add(PwYunQianDm $dm){
        return $this->_getDao()->add($dm->getData());
    }

    //编辑 **
    public function edit(PwYunQianDm $dm, $id){
        return $this->_getDao()->update($id, $dm->getData(), $dm->getIncreaseData(), $dm->getBitData());
    }
	
    //删除**
    public function delete($id){
        return $this->_getDao()->delete($id);
    }

	 //还原等级记录 **
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
        return Wekit::loadDao('SRC:extensions.yunqian.service.dao.App_YunQianDao');
    }
 }