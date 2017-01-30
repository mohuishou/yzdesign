<?php
Wind::import('APPS:admin.library.AdminBaseController');
/**
 * 后台访问入口
 *
 * @author 杨周 <yzhou91@aliyun-inc.com> QQ:89652519
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.yhcms.com
 * @package wind
 */

class CacheController extends AdminBaseController {
	public $page = 1;
	public $perpage = 20;

    
	public function doCacheAction(){
		$act = $this->getInput('act');
		$n = $this->getInput('n');
		if($act=='hy'){
			 if($n=='level'){
				$LevelClass= $this->_getYunQianCache()->_getLevel();//
				 $this->_getYyunQianLevelDs()->toLevel($LevelClass);
				 $this->showMessage('还原缓存成功');
			 }
		}
		$this->setTemplate('cache_run');
	}
	
	private function _getYyunQianLevelDs() {
        return Wekit::load('SRC:extensions.yunqian.service.App_YunQianLevel');
    }
	
	private function _getYunQianCache() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianCache');
    }
}

?>