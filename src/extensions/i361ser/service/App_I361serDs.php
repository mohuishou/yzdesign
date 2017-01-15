<?php
defined('WEKIT_VERSION') or exit(403);

class App_I361serDs {
	
	public function count($where){
		return $this->_getDao()->countByWhere($where);
	}
 
	//**
	public function getListByWhere($where, $limit, $start){
		return $this->_getDao()->getList($where, $limit, $start);	
	}
	
	public function add(App_I361serDm $dm) {
		if (true !== ($r = $dm->beforeAdd())) return $r;
		return $this->_getDao()->add($dm->getData());
	}
	
	public function update(App_I361serDm $dm) {
		if (true !== ($r = $dm->beforeUpdate())) return $r;
		return $this->_getDao()->update($dm->getField('id'), $dm->getData());
	}
	
	public function delete($id) {
		if (0 >= ($id = intval($id))) return array();
		return $this->_getDao()->delete($id);
	}
	
	public function get($id) {
		if (0 >= ($id = intval($id))) return array();
		return $this->_getDao()->get($id);	
	}
	
	public function updateByOid(App_I361serDm $dm) {
		if (true !== ($r = $dm->beforeUpdate())) return $r;
		$data = $dm->getData();
		unset($data['oid']);
		return $this->_getDao()->updateByOid($dm->getField('oid'), $data);
	}
	
	public function deleteByOid($oid) {
		return $this->_getDao()->deleteByOid($oid);
	}
	
	public function getByOid($oid) {
		return $this->_getDao()->getByOid($oid);	
	}
	
	private function _getDao() {
		return Wekit::loadDao('EXT:i361ser.service.dao.App_I361serDao');
	}
}