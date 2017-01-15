<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('EXT:i361ser.service.srv.EnDecrypt');

class PayController extends PwBaseController {
	
	public $config = array();
	public $id;
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			$this->showError('login.not', 'u/login/run');
		}
		if (!Wekit::C('app_i361ser', 'isopen')) $this->showError('点卡充值积分没有开启');
		$this->config = Wekit::C('app_i361ser');
	}
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$types = $this->_getService()->getTypes();
		$codes = $this->_getService()->getCodes();
		list($kahao, $password, $type, $code, $money) = $this->getInput(array('kahao','password','type','code','money'), 'post');
		if(!$types[$type]) $this->showError('充值卡类别错误');
		$_money = Pw::substrs($code, 4,2);
		if(!in_array($_money, $codes[$type])) $this->showError('充值金额错误'.$_money);
		if(!$kahao) $this->showError('请输入充值卡号');
		if(!$password) $this->showError('请输入充值卡密码');
		$error = $this->_postSer($kahao, $password, $type, $code, $money);
		if($error!='004')
		{
			$this->_getDs()->delete($this->id);
			$this->showError($this->_getService()->getEid($error));
		}
		Wind::import('EXT:i361ser.service.dm.App_I361serDm');
		$dm = new App_I361serDm();
		$dm->setId($this->id)->setState(4);
		$this->_getDs()->update($dm);
		$this->showMessage('success');
	}

	public function _postSer($kahao, $password, $type, $CardCode, $FaceMoney)
	{
		$TradeNumber = $this->addOrder($kahao, $password, $type, $CardCode, $FaceMoney);
		$Data = $kahao.','.$password;
		$FaceMoney = (int)$FaceMoney;
		$ReturnUrl = Wekit::C('site','info.url').'/pay/i361ser.php';
		$apiurl = "http://www.361ser.com/Api/serpay?";
    	$Sign = md5($this->config['partnerid'] . $TradeNumber . $CardCode . $Data . $FaceMoney . $ReturnUrl . $this->config['secretkey']);
    	$srcData = "TradeNumber=" . $TradeNumber . "&CardCode=" . $CardCode . "&Data=" . $Data . "&FaceMoney=" . $FaceMoney . "&ReturnUrl=" . $ReturnUrl . "&Sign=" . $Sign;
    	$url = $apiurl
            . "PartnerId=" . $this->config['partnerid']
            . "&EnData=" . urlencode(EnDecrypt::AesEncode($srcData, $this->config['secretkey']));
    	$code = file_get_contents($url);
    	return $code;
	}

	public function addOrder($kahao, $password, $type, $CardCode, $FaceMoney)
	{
		$rate = $this->config['czlv'][$type] ? $this->config['czlv'][$type] : 100;
		$number = floor($FaceMoney*$rate/100);
		Wind::import('EXT:i361ser.service.dm.App_I361serDm');
		$ordernno = $this->createOrderNo();
		$dm = new App_I361serDm();
		$dm->setUid($this->loginUser->uid)
			->setTime(Pw::getTime())
			->setOrderno($ordernno)
			->setPrice($FaceMoney)
			->setNumber($number)
			->setState(0)
			->setCtype($this->config['credit'])
			->setType($type)
			->setOrdernum(0)
			->setCode($CardCode)
			->setPassword('')
			->setKahao($kahao)
			->setMsg('处理中');
		$this->id = $this->_getDs()->add($dm);
		return $ordernno;
	}

	public function createOrderNo() {
		return '1' . str_pad(Wekit::getLoginUser()->uid, 4, "0", STR_PAD_LEFT) . Pw::time2str(Pw::getTime(), 'YmdHis') . WindUtility::generateRandStr(5);
	}

	protected function _getService() {
		return Wekit::load('EXT:i361ser.service.srv.App_I361ser_Service');
	}

	protected function _getDs() {
		return Wekit::load('EXT:i361ser.service.App_I361serDs');
	}
}
