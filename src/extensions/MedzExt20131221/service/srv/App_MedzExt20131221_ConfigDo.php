<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * ��̨�˵����
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_MedzExt20131221_ConfigDo {
	
	/**
	 * ��ȡ�ۺϺ�̨�˵�
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_MedzExt20131221' => array('�ۺ�', 'app/manage/*?app=MedzExt20131221', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>