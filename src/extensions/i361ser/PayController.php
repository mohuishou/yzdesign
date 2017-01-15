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
		if (!Wekit::C('app_i361ser', 'isopen')) $this->showError('点卡充值积分没有开启');
		$this->config = Wekit::C('app_i361ser');
	}

	public function run() {
		echo 'Are you technology? Please contact QQ:89652519';
		exit();
	}

	public function backAction()
	{
    	$SecretKey = $this->config['secretkey']; //密钥
    	$returnStr = '';
		$enData = $this->getInput('EnData', 'get');
    	$data =trim(urldecode(EnDecrypt::AesDecode($enData, $SecretKey)),"\0");
   		$dataLists = explode("&", $data);
	    $Sign = "";
	    $TradeNumber = ""; //商户内部的交易订单号
	    $OrderNum = ""; //信易通交易流水号
	    $Status = ""; //交易状态 1：处理成功，2：处理失败
	    $SuccMoney = ""; //成功金额
	    $CardNum = ""; //卡号
	    $FaiReason = ""; //失败原因
	    $RealMoney = ""; //实际面值
	    if (count($dataLists) != 8) {
	        $returnStr = "data error";
	    } else {
	        foreach ($dataLists as $dataList) {
	            $detailDataLists = explode("=", $dataList);
	            if (count($detailDataLists) != 2) {
	                $returnStr = "data error";
	                break;
	            }
	        }
	    }
	    if ($returnStr == "") {
	        foreach ($dataLists as $detaildata) {
	            $pervdata = explode("=", $detaildata);
	            $detailKey = $pervdata[0];
	            $detailValue = $pervdata[1];
	            if ($detailKey == "TradeNumber") {
	                $TradeNumber = $detailValue;
	            }
	            if ($detailKey == "OrderNum") {
	                $OrderNum = $detailValue;
	            }
	            if ($detailKey == "Status") {
	                $Status = $detailValue;
	            }
	            if ($detailKey == "SuccMoney") {
	                $SuccMoney = $detailValue;
	            }
	            if ($detailKey == "CardNum") {
	                $CardNum = $detailValue;
	            }
	            if ($detailKey == "FaiReason") {
	                $FaiReason = $detailValue;
	            }
	            if ($detailKey == "RealMoney") {
	                $RealMoney = $detailValue;
	            }
	            if ($detailKey == "Sign") {
	                $Sign = $detailValue;
	            }
	        }
	        $Sign1 = md5($TradeNumber . $OrderNum . $Status . $SuccMoney . $CardNum . $FaiReason . $RealMoney . $SecretKey);
	        if ($Sign1 == $Sign) {
				Wind::import('EXT:i361ser.service.dm.App_I361serDm');
	            if ($Status == "1") {
	                //数据操作
	                $order = $this->_getDs()->getByOid($TradeNumber);
	                if($order['state']=='1' || $order['state']=='2')
	                {
		                $returnStr = "ok";
					    echo $returnStr;
					    exit;
	                }
					$rate = $this->config['czlv'][$order['type']] ? $this->config['czlv'][$order['type']] : 100;
	                $number = floor($RealMoney*$rate/100);
        			Wind::import('SRV:credit.bo.PwCreditBo');
        			$creditBo = PwCreditBo::getInstance();
        			$types = $this->_getService()->getTypes();
					$dm = new App_I361serDm();
					$dm->setOid($TradeNumber)->setOrdernum($OrderNum)->setState(1)->setPrices($RealMoney)->setNumber($number)->setMsg('充值成功');
					$this->_getDs()->updateByOid($dm);
					$UserBo = new PwUserBo($order['created_userid']);
					$creditBo->addLog('i361ser_add', array($this->config['credit'] => +$number), $UserBo, array('type'=>$types[$order['type']]));
        			$creditBo->set($order['created_userid'], $this->config['credit'], +$number);
	                $returnStr = "ok";
				    echo $returnStr;
				    exit;
	            } else if ($Status == "2") {
	                //数据操作
					$dm = new App_I361serDm();
					$dm->setOid($TradeNumber)->setState(2)->setMsg($FaiReason);
					$this->_getDs()->updateByOid($dm);
	                $returnStr = "ok";
				    echo $returnStr;
				    exit;
	            }
	        } else {
	            $a=1;
	            ///签名失败
	        }
	    }
	    echo $returnStr;
	    exit;
	}

	protected function _getService() {
		return Wekit::load('EXT:i361ser.service.srv.App_I361ser_Service');
	}

	protected function _getDs() {
		return Wekit::load('EXT:i361ser.service.App_I361serDs');
	}
}
