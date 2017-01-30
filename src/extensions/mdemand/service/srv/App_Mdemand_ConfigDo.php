<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_Mdemand_ConfigDo {
	
	/**
	 * 获取设计需求后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_mdemand' => array('设计需求', 'app/manage/*?app=mdemand', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>