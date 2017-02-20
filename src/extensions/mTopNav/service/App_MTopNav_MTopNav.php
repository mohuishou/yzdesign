<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('EXT:mTopNav.service.dm.App_MTopNav_MTopNavDm');
/**
 * App_MTopNav_MTopNav - 数据服务接口
 *
 * @author 莫回首 <>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MTopNav_MTopNav {
	
	/**
	 * add record
	 *
	 * @param App_MTopNav_MTopNavDm $dm
	 * @return multitype:|Ambigous <boolean, number, string, rowCount>
	 */
	public function add(App_MTopNav_MTopNavDm $dm) {
		if (true !== ($r = $dm->beforeAdd())) return $r;
		return $this->_loadDao()->add($dm->getData());
	}
	
	/**
	 * update record
	 *
	 * @param App_MTopNav_MTopNavDm $dm
	 * @return multitype:|Ambigous <boolean, number, rowCount>
	 */
	public function update(App_MTopNav_MTopNavDm $dm) {
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
	 * @return App_MTopNav_MTopNavDao
	 */
	private function _loadDao() {
		return Wekit::loadDao('EXT:mTopNav.service.dao.App_MTopNav_MTopNavDao');
	}
}

?>