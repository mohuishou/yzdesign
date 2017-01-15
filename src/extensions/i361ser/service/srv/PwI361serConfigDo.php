<?php

class PwI361serConfigDo {
	
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
			'app_i361ser' => array('点卡换积分', 'app/manage/*?app=i361ser', '', '', 'appcenter'),
			);
		return $config;
	}

    /**
     * 获取积分配置
     *
     */
    public function getCreditConfig($config) {
        $config += array(
            'i361ser' => array('点卡充值', '', ''),
            'i361ser_add' => array('点卡充值积分', 'i361ser', '使用【{$type}】点卡充值成功;积分变化【{$cname}:{$affect}】', true),
        );
        return $config;
    }
	
    /**
     * 前台的用户资料扩展s_profile_menus
     *
     * @param array $config
     * @return array
     */
    public function getProfile($config) {
        $config['credit_tabs'] += array(
            'app_i361ser' => array('title' => '点卡充值积分', /*'url' => 'profile/index/run'*/),
        );
        return $config;
    }
}

?>