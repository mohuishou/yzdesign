<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 前台入口
 *
 * generated by phpwind.com
 */
class IndexController extends PwBaseController {
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		//判断是否登录
		$this->loginUser;
	}
	
	public function run() {
		//TODO Insert your code here
	}
}

?>