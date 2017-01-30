<?php
/**
 * 
 *
 */
class PwYunQianService {
	
	public function getMoney($mday=0){
		$conf = Wekit::C('yunqian');
		if(!$conf['money']) return 0;
		if($mday){
			if($mday>$conf['sum']){
				$money	=$conf['money'] + $conf['sum_money'];
			} else {
				$money=$conf['money'];	
			}
		} else {
			$money=$conf['money'];	
		}
		return $money;
	}
	
	public function setMoney($uid=0, $money=0){
		$conf = Wekit::C('yunqian');
		if(!$conf['money']) return true;
 		//积分配置
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $creditBo PwCreditBo */
		$creditBo = PwCreditBo::getInstance();
		$creditBo->addLog('yunqian_add', array($conf['credit'] => $money), Wekit::getLoginUser(), array('tdtime' => Pw::time2str(Pw::getTdtime(), 'Y-m-d')));
		$creditBo->sets($uid, array($conf['credit'] => $money));
	}
	
   //签到时间查询---24小时默认
   	public function isPost(){
	   	$times = Pw::getTime();
	   	$Tdtime = Pw::getTdtime();
		$conf = Wekit::C('yunqian');
	   	if($conf['time_sta']>$conf['time_end']){//跨夜时间处理
		   if($times<($Tdtime+($conf['time_sta']*3600)) && $times>($Tdtime+($conf['time_end']*3600))){
				return true;
		   }
	   	}else if($times_sta<$times_end){//正常时间处理
		   if($times<($Tdtime+($conf['time_sta']*3600)) || $times>($Tdtime+($conf['time_end']*3600))){
				 return true;
		   }
	   	}
	   return false;
   	}	
	public function getQduser($uid=0, $username){
		if(!$uid) return array();
		$qduser=$this->_getQianUserInfoDs()->getInfo($uid);
		if(!$qduser){
			//添加签到会员
			Wind::import('EXT:yunqian.service.dm.PwYunQianUserDm');
			$qduserdm = new PwYunQianUserDm();
			$qduserdm->setTime(0)
			->setAuthor($username)
			->setUid($uid)
			->setMoney(0)
			->addDay(0)
			->setMday(0)
			->setDtop(0)
			->setMtime(Pw::time2str(Pw::getTime(), 'Ym'))
			->setDj($Leveldb[0]['dj'])
			->setContent('');
			$this->_getQianUserInfoDs()->addInfo($qduserdm);
		}	
		return $this->_getQianUserInfoDs()->getInfo($uid);
	}
	//更新用户等级信息
	public function checkLevel($uid, $dj=1, $day=0){
		$level = $this->_getYunQianCache()->_getLevel($dj+1);//取得当前等级信息
		if($day>=$level['hits']){
			Wind::import('EXT:yunqian.service.dm.PwYunQianUserDm');
			$qduserdm = new PwYunQianUserDm($uid);
			$qduserdm->setDj($dj+1);
			$this->_getQianUserInfoDs()->updateInfo($qduserdm);
		}
		return true;
	}
	
	//检测用户签到排名
	public function checkdTop(){
		$count=$this->_getYunQianDs()->countByWhere(" and times > ".Pw::getTdtime());
		return $count+1;	
	}
	
	// **
    private function _getQianUserInfoDs() {
        return Wekit::load('SRC:extensions.yunqian.service.PwQianUserInfo');
    }
	
	// **
	private function _getYunQianCache() {
        return Wekit::load('SRC:extensions.yunqian.service.srv.PwYunQianCache');
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