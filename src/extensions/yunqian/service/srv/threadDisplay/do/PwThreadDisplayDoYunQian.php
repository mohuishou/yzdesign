<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:forum.srv.threadDisplay.do.PwThreadDisplayDoBase');
//Wind::import('SRV:like.srv.PwLikeService');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwThreadDisplayDoLike.php 24889 2013-02-25 08:29:24Z jieyin $ 
 * @package 
 */
class PwThreadDisplayDoYunQian extends PwThreadDisplayDoBase {
	
	public $loginUser;
	public $conf;

    public function __construct() {
		//$this->loginUser = Wekit::getLoginUser();
		//$this->conf = Wekit::C('yunqian');
    }
	
	/**
	 * 在这里输出插件内容 (位置：帖子操作按钮)
	 */
	public function createHtmlAfterUserInfo($user, $read) {
		$qduser=$this->_getQianService()->getQduser($user['uid'],$user['username']);
		if(!$qduser['qdxq']){
			return true;	
		}
		$data=array($read, $this->conf, $user, $qduser);
		PwHook::segment('EXT:yunqian.template.read_floor', array($data));
	}

	// **
    private function _getQianUserInfoDs() {
        return Wekit::load('SRC:extensions.yunqian.service.PwQianUserInfo');
    }
	// **
    private function _getQianService() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianService');
    }
}
?>