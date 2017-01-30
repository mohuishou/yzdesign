<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 签到统计
 *
 */

class PwQianUserInfo {
	
	/**
	 * 获取信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getInfo($uid) {
		if (empty($uid)) return array();
		return $this->_getDao()->get($uid);
	}
	/**
	 * 添加用户信息
	 *
	 * @param object $dm 更新信息
	 * @return bool
	 */
	public function addInfo(PwYunQianUserDm $dm) {
		if (($result = $dm->beforeAdd()) !== true) {
			return $result;
		}
		return $this->_getDao()->add($dm->getData());
	}
	
	/**
	 * 更新信息
	 *
	 * @param object $dm 更新信息
	 * @return bool
	 */
	public function updateInfo(PwYunQianUserDm $dm) {
		if (($result = $dm->beforeUpdate()) !== true) {
			return $result;
		}
		return $this->_getDao()->update($dm->id, $dm->getData(), $dm->getIncreaseData());
	}

	public function countByWhere($where){
		return $this->_getDao()->countByWhere($where);
	}
 
	//**
	public function getListByWhere($where, $limit, $start, $by=''){
		return $this->_getDao()->getList($where, $limit, $start, $by);	
	}
	protected function _getDao() {
		return Wekit::loadDao('EXT:yunqian.service.dao.Pw_YunQianUserInfoDao');
	}
}