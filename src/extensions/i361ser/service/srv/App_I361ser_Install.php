<?php
Wind::import('APPCENTER:service.srv.iPwInstall');
/**
 * 安装注入
 *
 * @author xiaoxia.xu<xiaoxia.xuxx@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: App_Majia_Install.php 24585 2013-02-01 04:02:37Z jieyin $
 * @package majia.service.srv
 */
class App_I361ser_Install implements iPwInstall {
	
	/* (non-PHPdoc)
	 * @see iPwInstall::install()
	 */
	public function install($install) {
		$defaultConfig = array('isopen' => array('value' => 0), 'czlv'=>array('value'=>array(
            '01'    => '',
            '05'    => '',
            '04'    => '',
            '15'    => '',
            '07'    => '',
            '14'    => '',
            '20'    => '',
            '11'    => '',
            '21'    => '',
            '30'    => '',
            '18'    => '',
            '10'    => '',
            '12'    => '',
            '06'    => '',
            '13'    => '',
            '17'    => '',
            '19'    => '',
            '08'    => '',
            '16'    => '',
            '09'    => ''
        )));
		/* @var $service PwConfig */
		$service = Wekit::load('config.PwConfig');
		$service->setConfigs('app_i361ser', $defaultConfig);
		WindFolder::mkRecur(APPS_PATH.'i361ser/controller/');
		$PayController = file_get_contents(EXT_PATH.'i361ser/PayController.php');
		file_put_contents(APPS_PATH.'i361ser/controller/PayController.php', $PayController);
		$i361ser = file_get_contents(EXT_PATH.'i361ser/i361ser.php');
		file_put_contents(ROOT_PATH.'pay/i361ser.php', $i361ser);
		$entrance_dir = CONF_PATH.'entrance.php';
		$entrance = file_get_contents($entrance_dir);
		$entrance = str_replace("'read.php' => 'bbs/read/run',","'read.php' => 'bbs/read/run','pay/i361ser.php' => 'i361ser/pay/back',",$entrance);
 		file_put_contents($entrance_dir, $entrance);
		return true;
	}
	
	/* (non-PHPdoc)
	 * @see iPwInstall::backUp()
	 */
	public function backUp($install) {
		//  Auto-generated method stub
	}
	
	/* (non-PHPdoc)
	 * @see iPwInstall::revert()
	 */
	public function revert($install) {
		//  Auto-generated method stub
	}
	
	/* (non-PHPdoc)
	 * @see iPwInstall::unInstall()
	 */
	public function unInstall($install) {
		/* @var $ds PwConfig */
		$ds = Wekit::load('config.PwConfig');
		$ds->deleteConfig('app_i361ser');
		WindFile::del(ROOT_PATH.'pay/i361ser.php');
		WindFolder::rm(APPS_PATH.'i361ser/', true);
		$entrance_dir = CONF_PATH.'entrance.php';
		$entrance = file_get_contents($entrance_dir);
		$entrance = str_replace("'read.php' => 'bbs/read/run','pay/i361ser.php' => 'i361ser/pay/back',","'read.php' => 'bbs/read/run',",$entrance);
 		file_put_contents($entrance_dir, $entrance);
		return true;
	}
	
	/* (non-PHPdoc)
	 * @see iPwInstall::rollback()
	 */
	public function rollback($install) {
		//  Auto-generated method stub
	}
}