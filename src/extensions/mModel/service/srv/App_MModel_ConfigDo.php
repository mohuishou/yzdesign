<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author 莫回首 <1@lailin.xyz>
 * @copyright http://lailin.xyz
 * @license http://lailin.xyz
 */
class App_MModel_ConfigDo {
	
	/**
	 * 获取模型后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_mModel' => array('模型', 'app/manage/*?app=mModel', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>