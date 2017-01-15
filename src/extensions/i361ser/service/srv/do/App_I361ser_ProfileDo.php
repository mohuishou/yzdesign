<?php
Wind::import('APPS:profile.service.PwProfileExtendsDoBase');

class App_I361ser_ProfileDo extends PwProfileExtendsDoBase {
	
	/* (non-PHPdoc)
	 * @see PwProfileExtendsDoBase::createHtml()
	 */
	public function createHtml($left, $tab) {
		$config = Wekit::C('app_i361ser');
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
		$method = isset($_GET['mymet']) ? $_GET['mymet'] : 'chongzhi';
		$mydata = array('creditBo'=>$creditBo);
		$loginUser = Wekit::getLoginUser();
		if($method=='order')
		{
			$perpage = 20;
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
	        $page = $page < 1 ? 1 : $page;
	        list($offset, $limit) = Pw::page2limit($page, $perpage);
			$args = array('_left'=>'credit','_tab'=>'app_i361ser','mymet'=>'order');
			$where = " and `created_userid`='".$loginUser->uid."'";
        	$total = $this->_getDs()->count($where);
        	$list = $total ? $this->_getDs()->getListByWhere($where, $limit, $offset) : array();
        	$mydata = array('list'=>$list,'total'=>$total,'page'=>$page,'args'=>$args,'perpage'=>$perpage, 'creditBo'=>$creditBo);
		}
		else if($method=='view')
		{

		}
		PwHook::template($method, 'EXT:i361ser.template.index_run', true, $this->bp->user, $config, $this->_getService()->getTypes(),$this->_getService()->getCodes(),$mydata);
	}

	protected function _getService() {
		return Wekit::load('EXT:i361ser.service.srv.App_I361ser_Service');
	}

	protected function _getDs() {
		return Wekit::load('EXT:i361ser.service.App_I361serDs');
	}
}