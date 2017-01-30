<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 签到统计
 *
 */

class PwQianInfo {
	
	/**
	 * 获取论坛信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getInfo($id) {
		if (empty($id)) return array();
		return $this->_getDao()->get($id);
	}
	
	/**
	 * 更新论坛信息
	 *
	 * @param object $dm 更新信息
	 * @return bool
	 */
	public function updateInfo(PwQianinfoDm $dm) {
		if (($result = $dm->beforeUpdate()) !== true) {
			return $result;
		}
		return $this->_getDao()->update($dm->id, $dm->getData(), $dm->getIncreaseData());
	}

	protected function _getDao() {
		return Wekit::loadDao('EXT:yunqian.service.dao.Pw_YunQianInfoDao');
	}
}