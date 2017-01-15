<?php
Wind::import('APPS:admin.library.AdminBaseController');

class ManageController extends AdminBaseController {

    public function run() {
		$config=Wekit::C('app_i361ser');
		$this->setOutput($config, 'config');
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
        $this->setOutput($creditBo, 'creditBo');
    }
    
 	public function doRunAction() {
 		$arr = $this->getInput('config', 'post');
 		$config = new PwConfigSet('app_i361ser');
 		foreach($arr as $k => $v){
 			if(!$v) $v= '';
			$config->set($k, $v)->flush();
 		}
		$this->showMessage('success');
 	}

 	public function typeAction()
 	{
 		$type = $this->_getService()->getTypes();
		$this->setOutput($type, 'type');
		$config=Wekit::C('app_i361ser');
		$this->setOutput($config, 'config');
 	}

 	public function doTypeAction() {
 		$arr = $this->getInput('config', 'post');
 		$config = new PwConfigSet('app_i361ser');
 		foreach($arr as $k => $v){
 			if(!$v) $v= '';
			$config->set($k, $v)->flush();
 		}
		$this->showMessage('success');
 	}

	protected function _getService() {
		return Wekit::load('EXT:i361ser.service.srv.App_I361ser_Service');
	}
}

?>