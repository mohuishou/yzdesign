<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_MedzExt20131221_ConfigDo {
	
	/**
	 * 获取聚合后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_MedzExt20131221' => array('聚合', 'app/manage/*?app=MedzExt20131221', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>