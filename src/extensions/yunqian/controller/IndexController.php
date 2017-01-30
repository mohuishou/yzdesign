<?php
defined('WEKIT_VERSION') || exit('Forbidden');
/**
 * 
 *
 * @author 杨周 <yzhou91@aliyun-inc.com> QQ:89652519
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.yhcms.com
 * @package wind
 */
class IndexController extends PwBaseController {
	
	public $page = 1;
	public $perpage = 50;
	public  $conf;
 
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$this->conf = Wekit::C('yunqian');
		if(!$this->conf['isopen']) $this->showError('系统没有开启');
		if(!$this->loginUser->uid) $this->showError('请登录后查看','u/login/run');
		!$this->loginUser->getPermission('yunqian') && $this->showError('你没有权限使用每日一签', 'bbs/index/run');
	}
	
    public function run() {
		$this->init();
		//分页处理
		$page = intval($this->getInput('page'));
		$type = $this->getInput('type');
		$this->page = $page < 1 ? 1 : $page;
		list($offset, $limit) = Pw::page2limit($this->page, $this->perpage);
		$args=array();
		if($type=='month'){
			$args['type']=$type;
			$total = $this->_getQianUserInfoDs()->countByWhere(" and day>0 and mday>0 and mtime='".Pw::time2str(Pw::getTime(), 'Ym')."'");
 			$list = $total ? $this->_getQianUserInfoDs()->getListByWhere(" and day>0 and mday>0 and mtime='".Pw::time2str(Pw::getTime(), 'Ym')."'", $limit, $offset , 'mday desc') : array();
		} else if($type=='zong'){
			$args['type']=$type;
			$total = $this->_getQianUserInfoDs()->countByWhere();
 			$list = $total ? $this->_getQianUserInfoDs()->getListByWhere(' and day>0', $limit, $offset , 'day desc') : array();
		} else {
			$total = $this->_getQianUserInfoDs()->countByWhere(" and day>0 and times>".Pw::getTdtime());
 			$list = $total ? $this->_getQianUserInfoDs()->getListByWhere(" and day>0 and times>".Pw::getTdtime(), $limit, $offset ) : array();
		}
		$this->setOutput($total, 'count');
		$this->setOutput($list, 'list');
		$this->setOutput($args, 'args');
		$this->setOutput($this->page, 'page');
		$this->setOutput($this->perpage, 'perpage');
		$_count = Pw::collectByKey($this->_getYunQianDs()->countLei(" and times>".Pw::getTdtime()), 'count');
		rsort($_count);
		$leiCount = Pw::orderByKeys($this->_getYunQianDs()->countLei(" and times>".Pw::getTdtime()), 'count', $_count);
		$leiCount = array_slice($leiCount,0,5,true);
		$this->setOutput($leiCount, 'leiCount');
    }
	
	//公共部分
	private function init(){
		$Leveldb=$this->_getYunQianLevelDs()->getListByWhere('', 0 ,100);
		$leidb=$this->_getYunQianCache()->getLei();
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
		$qduser=$this->_getQianService()->getQduser($this->loginUser->uid,$this->loginUser->username);
		$this->setOutput(explode("\n", $this->conf['qdcontent']), 'lrbdb');
		$this->setOutput($this->conf, 'config');
		
		//$_userend=$this->_getYunQianDs()->getInfoByUid($this->loginUser->uid);//会员最后一次签到
		$infos=Wekit::load('EXT:yunqian.service.PwQianInfo')->getInfo(1);//签到统计信息
		$this->setOutput($qduser, 'qduser');//签到会员信息
		$this->setOutput($infos, 'infos');//统计信息
		$this->setOutput($creditBo, 'creditBo');
		$this->setOutput($Leveldb, 'Leveldb');
		$this->setOutput($leidb, 'leidb');
		$this->setOutput($this->_getQianService()->isPost(), 'ispost');
	}

	/**
	 * YunQian
	 * 
	 * @return YunQian
	 */
	private function _getYunQianDs(){
		return Wekit::load('EXT:yunqian.service.App_YunQian');
	}
	
	// **
    private function _getYunQianLevelDs() {
        return Wekit::load('SRC:extensions.yunqian.service.App_YunQianLevel');
    }
	// **
    private function _getQianUserInfoDs() {
        return Wekit::load('SRC:extensions.yunqian.service.PwQianUserInfo');
    }
    
	// **
    private function _getQianService() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianService');
    } 
	// **
	private function _getYunQianCache() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianCache');
    }
}