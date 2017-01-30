<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('EXT:mdemand.service.dm.App_Mdemand_MdemandDm');
/**
 * App_Mdemand_Mdemand - 数据服务接口
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_Mdemand_Mdemand {
	
	/**
	 * add record
	 *
	 * @param App_Mdemand_MdemandDm $dm
	 * @return multitype:|Ambigous <boolean, number, string, rowCount>
	 */
	public function add(App_Mdemand_MdemandDm $dm) {
		if (true !== ($r = $dm->beforeAdd())) return $r;
		return $this->_loadDao()->add($dm->getData());
	}
	
	/**
	 * update record
	 *
	 * @param App_Mdemand_MdemandDm $dm
	 * @return multitype:|Ambigous <boolean, number, rowCount>
	 */
	public function update(App_Mdemand_MdemandDm $dm) {
		if (true !== ($r = $dm->beforeUpdate())) return $r;
		return $this->_loadDao()->update($dm->getId(), $dm->getData());
	}
	
	/**
	 * get a record
	 *
	 * @param unknown_type $id
	 * @return Ambigous <multitype:, multitype:unknown , mixed>
	 */
	public function get($id) {
		return $this->_loadDao()->get($id);
	}
	
	/**
	 * delete a record
	 *
	 * @param unknown_type $id
	 * @return Ambigous <number, boolean, rowCount>
	 */
	public function delete($id) {
		return $this->_loadDao()->delete($id);
	}
	
	/**
	 * @return App_Mdemand_MdemandDao
	 */
	private function _loadDao() {
		return Wekit::loadDao('EXT:mdemand.service.dao.App_Mdemand_MdemandDao');
	}
}

?>