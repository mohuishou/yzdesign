<?php
Wind::import('LIB:base.PwBaseController');
Wind::import('SRV:credit.bo.PwCreditBo');
Wind::import('EXT:yunqian.service.dm.PwQianinfoDm');

/**
 * 每日打卡
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PunchController extends PwBaseController {
	
	protected $config = array();
	protected $perpage = 20;
	protected $_creditBo;
	public  $conf;
	public  $content;
	public  $tdtime;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1) {
			$this->showError('USER:user.not.login');
		}
		$this->conf = Wekit::C('yunqian');
		if(!$this->conf['isopen']) $this->showError('系统没有开启');
		$this->config = Wekit::C()->getValues('site');
		$this->_creditBo = PwCreditBo::getInstance();
		!$this->loginUser->getPermission('yunqian') && $this->showError('你没有权限使用每日一签');
		$this->tdtime = Pw::str2time(Pw::time2str(Pw::getTime(), 'Y-m-d'));
		// 是否开启 取消后台打开开关，使用签到开关
//		if(!$this->config['punch.open']){
//			$this->showError('SPCAE:punch.not.open');
//		}
	}
	
	/** 
	 * 自己打卡签到
	 *
	 */
	public function punchAction() {
		$userInfo = $this->loginUser->info;
		/*签到应用开始*/
		list($qdxq, $content, $qdmode) = $this->getInput(array('qdxq', 'qdcontent', 'qdmode'), post);
		$_infos=$this->_getQianUserInfoDs()->getInfo($this->loginUser->uid);
		if($this->_getQianService()->isPost()){
			$this->showError('请在每天'.$this->conf['time_sta'].'点到'.$this->conf['time_end'].'点进行签到！');	
		}
		if($_infos&&$_infos['times']>$this->tdtime){
			$this->showError('今天已经签到了，明天再来吧！');	
		}
		if(!$qdxq || $qdxq>9 || $qdxq<1){
			$this->showError('你选择的心情不正确，请重新选择签到心情！');	
		}
		if(!$content&&$qdmode!=3){
			$this->showError('请输入点签到内容！');	
		}
		if(WindValidator::hasHtml($content)){
			$this->showError('签到内容含有非法内容，请重新输入！');	
		}
		if(Pw::strlen($content)>100){
			$this->showError('签到内容长度请控制在100字符内！');
		}
		!$content && $content = '这人很懒，什么都没留下。';
		$this->content = $content;
		/*签到应用结束*/
		// 是否自己打过了
		if ($userInfo['punch']) {
			$punchData = unserialize($userInfo['punch']);
			$havePunch = $this->_getPunchService()->isPunch($punchData);
			if ($havePunch) {
				($punchData['username'] == $userInfo['username']) && $this->showError('SPACE:punch.today.punch');
				$helpPunch = 1;
			}
		}
		// 奖励积分数
		$reward = $this->config['punch.reward'];
		$behavior = $this->_getUserBehaviorDs()->getBehavior($userInfo['uid'],'punch_day');
		$steps = $behavior['number'] > 0 ? $behavior['number']: 0;
		$helpPunch && $steps = $steps - 1 > 0 ? $steps - 1 : 0;
		$awardNum = $reward['min'];
		$steps && $awardNum = ($reward['min'] + $steps * $reward['step'] > $reward['max']) ? $reward['max'] : $reward['min'] + $steps * $reward['step'];
		if ($havePunch) {
			$reduce = $awardNum - $this->config['punch.friend.reward']['rewardMeNum'];
			$awardNum = $reduce > 0 ? $reduce : 0;
		}
		$behaviorNum = $havePunch ? $behavior['number'] : $behavior['number']+1;
		// 更新用户数据，记录行为
		$result = $this->_punchBehavior($userInfo,$awardNum,$behaviorNum);
		if ($result instanceof PwError) {
			$this->showError($result->getError());
		}
		// 奖励积分
		if ($awardNum) {
			$this->_creditBo->addLog('punch', array($reward['type']=>$awardNum), $this->loginUser,  array(
				'cname' => $this->_creditBo->cUnit[$reward['type']],
				'affect' => $awardNum)
			);
			$this->_creditBo->set($userInfo['uid'],$reward['type'],$awardNum);
		}
		$result = array(
			'behaviornum' => $havePunch ? $behavior['number'] : $behavior['number']+1,
			'reward' => $awardNum. $this->_creditBo->cUnit[$this->config['punch.reward']['type']] . $this->_creditBo->cType[$this->config['punch.reward']['type']]
		);
		$this->_addLog();/*签到应用*/
		Pw::echoJson(array('state' => 'success', 'data' => $result));exit;
	}
	
	/** 
	 * 请求获取tip
	 *
	 */
	public function punchtipAction() {
		$punchData = $this->loginUser->info['punch'];
		$punchData = $punchData ? unserialize($punchData) : array();
		$reward = $this->config['punch.reward'];
		if (!$punchData) {
			$data = array(
				'cUnit' => $this->_creditBo->cUnit[$reward['type']],
				'cType' => $this->_creditBo->cType[$reward['type']],
				'todaycNum' => $reward['min'],
				'tomorrowcNum' => $reward['min']+$reward['step'],
				'step'	=> $reward['step'],
				'max'	=> $reward['max']
			);
			Pw::echoJson(array('state' => 'success', 'data' => $data));exit;
		}
		$havePunch = $this->_getPunchService()->isPunch($punchData);
		if($punchData['username'] == $this->loginUser->username && $havePunch){
			Pw::echoJson(array('state' => 'fail'));exit;
		}
		$behavior = $this->_getUserBehaviorDs()->getBehavior($this->loginUser->uid,'punch_day');
		$steps = $behavior['number'] > 0 ? $behavior['number']: 0;
		$awardNum = ($reward['min'] + $steps * $reward['step'] > $reward['max']) ? $reward['max'] : $reward['min'] + $steps * $reward['step'];
		$tomorrowcNum = $awardNum+$reward['step'];
		$data = array(
			'cUnit' => $this->_creditBo->cUnit[$reward['type']],
			'cType' => $this->_creditBo->cType[$reward['type']],
			'todaycNum' => $awardNum,
			'tomorrowcNum' => $tomorrowcNum > $reward['max'] ? $reward['max'] : $tomorrowcNum,
			'step'	=> $reward['step'],
			'max'	=> $reward['max']
		);
		Pw::echoJson(array('state' => 'success', 'data' => $data));exit;
	}
	
	/** 
	 * 打卡 - 更新用户数据
	 *
	 * @param int $uid 	
	 * @return bool
	 */
	private function _punchBehavior($userInfo,$awardNum,$behaviorNum = '') {
		$reward = $this->config['punch.reward'];
		$punchData = array(
			'username' => $this->loginUser->username,
			'time' => Pw::getTime(),
			'cNum' => $awardNum,
			'cUnit' => $this->_creditBo->cUnit[$reward['type']],
			'cType' => $this->_creditBo->cType[$reward['type']],
			'days'  => $behaviorNum,
		);

		// 更新用户data表信息
		Wind::import('SRV:user.dm.PwUserInfoDm');
		$dm = new PwUserInfoDm($userInfo['uid']);
		$dm->setPunch($punchData);
		$this->_getUserDs()->editUser($dm, PwUser::FETCH_DATA);
		
		//埋点[s_punch]
		PwSimpleHook::getInstance('punch')->runDo($dm);
		$this->_editInfo();
		$this->_editUserInfo();
		//记录行为
		return $this->_getUserBehaviorDs()->replaceBehavior($userInfo['uid'],'punch_day',$punchData['time']);
	}
	
	public function _editInfo(){
		//更新统计信息
		$infos=Wekit::load('EXT:yunqian.service.PwQianInfo')->getInfo(1);
		$infoDm = new PwQianinfoDm();
		if($infos['dtime']!=$this->tdtime&&$infos['dtime']<$this->tdtime){
			$infoDm->setDtime($this->tdtime)->setDqian(1)->setNewmember($this->loginUser->username)->setnewUid($this->loginUser->uid);
			if($infos['dqian']>$infos['higholnum']){
				$infoDm->setHigholnum($infos['dqian'])->setHigholtime($infos['dtime']);	
			}
			$infoDm->setZqian($infos['dqian']);
			$this->conf['isopen_post'] && $tid = $this->_getQianPostService()->dopost($this->conf['post_fid']);
			$tid && $infoDm->setDtid($tid);
			if($this->conf['isopen_post'] && $tid)$this->_getQianPostService()->doreply($this->content, $this->conf['post_fid'], $tid, $this->loginUser->uid, $this->loginUser->username);
		} else {
			$infoDm->addDqian(1);
			$this->conf['isopen_post'] && $this->_getQianPostService()->doreply($this->content, $this->conf['post_fid'], $infos['dtid'], $this->loginUser->uid, $this->loginUser->username);
		}
		if(!$infos['mtime']||$infos['mtime']!=Pw::time2str(Pw::getTime(), 'Ym')||$infos['mtime']<Pw::time2str(Pw::getTime(), 'Ym')){
			$infoDm->setMqian(1)->setMtime(Pw::time2str(Pw::getTime(), 'Ym'));
		} else {
			$infoDm->addMqian(1);
		}
		$infoDm->addTotal(1);
		Wekit::load('EXT:yunqian.service.PwQianInfo')->updateInfo($infoDm);
		return true;
	}
	
	public function _editUserInfo(){
		list($qdxq, $content) = $this->getInput(array('qdxq', 'qdcontent'), post);
		$qduser=$this->_getQianService()->getQduser($this->loginUser->uid,$this->loginUser->username);
		Wind::import('EXT:yunqian.service.dm.PwYunQianUserDm');
		$qduserdm = new PwYunQianUserDm($this->loginUser->uid);
		$qduserdm->addDay(1)->setTime(Pw::getTime());
		if(!$qduser['mtime']||$qduser['mtime']!=Pw::time2str(Pw::getTime(), 'Ym')||$qduser['mtime']<Pw::time2str(Pw::getTime(), 'Ym')){
			$qduserdm->setMday(1)->setMtime(Pw::time2str(Pw::getTime(), 'Ym'));
		} else {
			$qduserdm->addMday(1);	
		}
		$dTop=$this->_getQianService()->checkdTop();
		$qduserdm->setContent($content)->setQdxq($qdxq)
			->setDtop($dTop);
		$_money = $this->_getQianService()->getMoney($qduser['mday']+1);
		if($_money){
			$this->_getQianService()->setMoney($this->loginUser->uid, $_money);
			$qduserdm->addMoney($_money);
			$infoDm = new PwQianinfoDm();
			$infoDm->addMoney($_money);
			Wekit::load('EXT:yunqian.service.PwQianInfo')->updateInfo($infoDm);
		}	
		$this->_getQianUserInfoDs()->updateInfo($qduserdm);
		$this->_getQianService()->checkLevel($this->loginUser->uid, $qduser['dj'], $qduser['day']+1);
	}
	
	public function _addLog(){
		$userInfo = $this->loginUser;
		list($qdxq, $content) = $this->getInput(array('qdxq', 'qdcontent'), post);
		$qduser=$this->_getQianService()->getQduser($this->loginUser->uid,$this->loginUser->username);
		//添加签到日志
		Wind::import('EXT:yunqian.service.dm.PwYunQianDm');
		$dm = new PwYunQianDm();
		$dm->setTime(Pw::getTime())->setAuthor($userInfo->username)->setUid($userInfo->uid)->setQdxq($qdxq)->setIp($userInfo->ip)->setContent($content);
		$_money = $this->_getQianService()->getMoney($qduser['mday']+1);
		if($_money){
			$dm->setMoney($_money);
		}	
		$this->_getYunQianDs()->add($dm);
	}
	
	/**
	 * PwUserBehavior
	 * 
	 * @return PwUserBehavior
	 */
	private function _getUserBehaviorDs() {
		return Wekit::load('user.PwUserBehavior');
	}
	
	/**
	 * PwPunchService
	 * 
	 * @return PwPunchService
	 */
	private function _getPunchService(){
		return Wekit::load('space.srv.PwPunchService');
	}
	
	/**
	 * PwUser
	 * 
	 * @return PwUser
	 */
	private function _getUserDs(){
		return Wekit::load('user.PwUser');
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
    private function _getQianUserInfoDs() {
        return Wekit::load('SRC:extensions.yunqian.service.PwQianUserInfo');
    }
	
	// **
    private function _getQianService() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianService');
    }
	// **
    private function _getQianPostService() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianPostService');
    }
}