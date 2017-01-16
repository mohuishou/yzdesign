<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单扩展
 */
class Appjsjpay_Admin_MenuDo {
	
	/**
	 *后台菜单配置s_admin_menu
	 * @param array $config
	 * @return array
	 */
	public function appjsjpayDo($config) {
		$config += array(
			'jsjpay' => array('支付宝在线充值', 'app/manage/*?app=jsjpay', '', '', 'appcenter'),
			);
		return $config;
	}

	/**
     	* 前台的用户资料扩展s_profile_menus
     	* @param array $config
     	* @return array
     	*/
	public function getProfile($config) {
        	$config['credit_tabs'] += array(
            		'jsjpay' => array(title => '在线充值'),
        	);
		return $config;
	}

}

?>