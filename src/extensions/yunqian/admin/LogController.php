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

class LogController extends AdminBaseController {
	
	public $page = 1;
	public $perpage = 20;

 //签到记录
 	public function run() {
		$service = Wekit::load('config.PwConfig');
 		$config = $service->getValues('Yhqdao');
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
        $credit_name = $creditBo->cType[$config[credit]];
		$credit_unit = $creditBo->cUnit[$config[credit]];
		$page = intval($this->getInput('page'));
        $this->page = $page < 1 ? 1 : $page;
        list($offset, $limit) = Pw::page2limit($this->page, $this->perpage);
        $total = $this->_getYunQianDs()->count();
        $list = $total ? $this->_getYunQianDs()->getListByWhere('',$limit, $offset) : array();
		$this->setOutput($total, 'total');
        $this->setOutput($list, 'list');
		$this->setOutput($this->page, 'page');
		$this->setOutput($this->perpage, 'perpage');
		$this->setTemplate('log_run');
		$this->setOutput($credit_name, 'credit_name');
		$this->setOutput($credit_unit, 'credit_unit');
 	}
	//删除签到记录信息
	public function delAction() {
		$idArr = $this->getInput('idll','post');
		if(!$idArr){
			$this->showError('请选择删除记录');
		}
		foreach($idArr as $id){
			$this->_getYunQianDs()->delete($id);	
		}
		$this->showMessage('删除记录成功');
	}
	

	/**
	 * YunQian
	 * 
	 * @return YunQian
	 */
	private function _getYunQianDs(){
		return Wekit::load('EXT:yunqian.service.App_YunQian');
	}
}

?>