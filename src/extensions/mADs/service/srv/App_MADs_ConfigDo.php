<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MADs_ConfigDo {
	
	/**
	 * 获取广告助手后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_mADs' => array('广告助手', 'app/manage/*?app=mADs', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>