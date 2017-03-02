<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');

class ManageController extends AdminBaseController {
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		//TODO do something before all the action
	}
	
	public function run() {
		//TODO Insert your code here!
	}
}

?>