<?php
Wind::import('APPS:admin.library.AdminBaseController');

class ListwController extends AdminBaseController {
	
	public $page = 1;
	public $perpage = 20;

 	public function run() {
 		$key = $this->getInput('key');
 		$keyword = $this->getInput('keyword');
 		$stime = $this->getInput('stime');
 		$etime = $this->getInput('etime');
		$page = intval($this->getInput('page'));
        $this->page = $page < 1 ? 1 : $page;
        list($offset, $limit) = Pw::page2limit($this->page, $this->perpage);
		$args = array();
		$where = " ";
		if($keyword && $key)
		{
			$args['key'] = $key;
			$args['keyword'] = $keyword;
			$where .=" and `".$key."` like '%$keyword%' ";
		}
		if($stime)
		{
			$args['stime'] = $stime;
			$where .=" and `created_time`> " . Pw::str2time($stime);
		}
		if($etime)
		{
			$args['etime'] = $etime;
			$where .=" and `created_time` < " . Pw::str2time($etime);
		}
        $total = $this->_getDs()->count($where);
        $list = $total ? $this->_getDs()->getListByWhere($where, $limit, $offset) : array();
		$this->setOutput($total, 'total');
        $this->setOutput($list, 'list');
		$this->setOutput($this->page, 'page');
		$this->setOutput($args, 'args');
		$this->setOutput($this->perpage, 'perpage');
 		$type = $this->_getService()->getTypes();
		$this->setOutput($type, 'type');
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
        $this->setOutput($creditBo, 'creditBo');
 	}

 	public function deleteAction(){
 		$id = $this->getInput('id', 'get');
 		$this->_getDs()->delete($id);
		$this->showMessage('success');
 	}

	protected function _getDs() {
		return Wekit::load('EXT:i361ser.service.App_I361serDs');
	}

	protected function _getService() {
		return Wekit::load('EXT:i361ser.service.srv.App_I361ser_Service');
	}
}

