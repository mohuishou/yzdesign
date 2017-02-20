<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author 莫回首 <>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MTopNav_ConfigDo {
	
	/**
	 * 获取顶部导航后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_mTopNav' => array('顶部导航', 'app/manage/*?app=mTopNav', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>