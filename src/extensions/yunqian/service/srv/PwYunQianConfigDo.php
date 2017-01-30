<?php
/**
 * 配置类扩展
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package album
 */
class PwYunQianConfigDo {
	
    protected $configKey = 'yunqian';
	protected $loginUser;



    public function __construct(){
        $this->loginUser = Wekit::getLoginUser();
    }
	
	/**
	 * 获取后台菜单
	 *
	 * @param array $config
	 * @return multitype:multitype:string  
	 */
	public function getAdminMenu($config) {
		$config += array(
			'app_yunqian' => array('每日一签', 'app/manage/*?app=yunqian', '', '', 'appcenter'),
			);
		return $config;
	}
	
	/**
	 * 
	 *
	 * @param unknown_type $config
	 * @return multitype:multitype:string  multitype:string boolean  
	 */
	public function getCreditConfig($config) {
		$config += array(
			'yunqian' => array('签到', '', ''),
			'yunqian_add' => array('每日一签', 'yunqian', '{$tdtime}签到成功;积分变化【{$cname}:{$affect}】', false));
		return $config;
	}
	/**
     * 获取后台权限设置 - 具体权限 钩子调用 s_permissionConfig
     *
     */

    public function getPermissionConfig($config){
        if(!is_array($config) || empty($config)) return false;
        $config[$this->configKey] = array('radio', 'basic', '使用签到', '');
        return $config;
    }
	/**
     *
     * 获取后台权限设置 - 权限类别  钩子调用 s_permissionCategoryConfig
     */

    public function getPermissionCategoryConfig($config){
        if(!is_array($config) || empty($config)) return false;
        $config['other']['sub'] += array(
            $this->configKey => array(
                'name' => '签到设置',
                'items' => array(
                    $this->configKey
                )
            ),
         );
        return $config;
    }
	
    private function _getUserBo(){
        Wind::import('SRV:user.bo.PwUserBo');
        return PwUserBo::getInstance($this->loginUser->uid);

    }
	
}

?>