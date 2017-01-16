<?php
/**
 * 应用前台入口
 *金沙江出品 客服QQ109588550 
 *近来发现很多站长朋友私自更改代码造成插件无法访问
 *所以敬告站长朋友：此页为核心代码，请勿私自修改！
 *多沟通，多提问，有问题就联系我们。
 */
Wind::import('APPS:admin.library.AdminBaseController');

class ManageController extends AdminBaseController {

	public function run() {
		$config = Wekit::C('jsjpay');
        	Wind::import('SRV:credit.bo.PwCreditBo');
        	$creditBo = PwCreditBo::getInstance();
        	$this->setOutput($creditBo, 'creditBo');
		$this->setOutput($config, 'config');
	}
    
 	public function doRunAction() {
		$config = $this->getInput('config', 'post');

		if(!is_numeric($config['bili']) || !is_numeric($config['apiid']) || !is_numeric($config['min'])){
			$this->showError('充值比例、最小数量、Api-id请输入数字');
		}
 		if($config['bili']<='0' || $config['min']<='0') {
			$this->showError('充值比例、最小数量输入错误！');
		}


		if($config['apiid']<'12345'){
			$this->showError('请输入正确的5位数字Api-id');
		}
		if(strlen($config['apikey'])<'32'){
			$this->showError('请输入正确的32位英文数字混合型Api-key');
		}

		$configSet = new PwConfigSet('jsjpay');
		$configSet->set('apiid',trim($config['apiid']))
				->set('apikey',trim($config['apikey']))
				->set('bili',trim($config['bili']))
				->set('min',trim($config['min']))
				->set('name',trim($config['name']))
				->set('isopen', abs($config['isopen']))
				->set('credit',trim($config['credit']));
		$configSet->flush();
		$this->showMessage('config.setting.success');
 	}

}

?>