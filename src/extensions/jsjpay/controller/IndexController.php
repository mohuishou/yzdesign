<?php
/**
 * 应用前台入口
 *金沙江出品 客服QQ109588550 
 *近来发现很多站长朋友私自更改代码造成插件无法访问
 *所以敬告站长朋友：此页为核心代码，请勿私自修改！
 *多沟通，多提问，有问题就联系我们。
 */
class IndexController extends PwBaseController {

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$config = Wekit::C('jsjpay');

		if (!$config['isopen']) {
			$this->showError('插件未开启');
		}
	}

	public function run() {		
		if($_GET['act']=='bd'){
			$_POST['csrf_token'] = Wind::getComponent('windToken')->saveToken('csrf_token');
			$uid = !empty($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];
			$addnum = !empty($_POST['addnum']) ? $_POST['addnum'] : $_GET['addnum'];
			$total = !empty($_POST['total']) ? $_POST['total'] : $_GET['total'];

			if ($this->loginUser->gid!=3) {
				$this->showError('您没有权限访问此页面 ╮(╯▽╰)╭');exit;
			}
			if (!$uid || !$addnum || !$total){
				$this->showError('缺少补单的必要参数 ╮(╯▽╰)╭');exit;
			}
			if (!is_numeric($uid) || !is_numeric($total)){
				$this->showError('UID和人民币只能是整数 ╮(╯▽╰)╭');exit;
			}

			$uid = intval($uid);
			$addnum = strip_tags($addnum);
			$total = strip_tags($total);

			$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
			$num = $mysql->getsaddnum($addnum);
			if($num['op']=='1'){
				$this->showError('订单状态正常，无需补单 ╮(╯▽╰)╭');exit;
			}else if($num['op']=='9'){
				$config = Wekit::C('jsjpay');
				$k = Wekit::C('jsjpay', 'credit');
				$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
				Wind::import('SRV:credit.bo.PwCreditBo');
				$creditBo = PwCreditBo::getInstance();
				$cnums =  $total*$config['bili'];
				$cname = $creditBo->cType[$k];
            			$msgname = '<font color=green>充值成功！</font>';
				$msgmore = '<font color=green>你已成功充值 <b>'.$cnums.$cname.'</b></font>    <a href=index.php?m=profile&c=extends&_left=credit&_tab=jsjpay>点此查看记录</a>';
				$creditBo->set($num['uid'], $k, +$cnums);
				Wekit::load('message.srv.PwNoticeService')->sendDefaultNotice($num['uid'],$msgmore,$msgname);
				$id = intval($num['id']);				
				$data = array();
				$data['op'] = '1';
				$data['total'] = $total;
				$data['time'] = Pw::getTime();
				$mysql->update($id,$data);
				$this->showError('补单成功 O(∩_∩)O~');
			}else if(!$num){
				$config = Wekit::C('jsjpay');
				$k = Wekit::C('jsjpay', 'credit');
				$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
				Wind::import('SRV:credit.bo.PwCreditBo');
				$creditBo = PwCreditBo::getInstance();
				$cnums =  $total*$config['bili'];
				$cname = $creditBo->cType[$k];
            			$msgname = '<font color=green>充值成功！</font>';
				$msgmore = '<font color=green>你已成功充值 <b>'.$cnums.$cname.'</b></font>    <a href=index.php?m=profile&c=extends&_left=credit&_tab=jsjpay>点此查看记录</a>';
				$creditBo->set($uid, $k, +$cnums);
				Wekit::load('message.srv.PwNoticeService')->sendDefaultNotice($uid,$msgmore,$msgname);				
				$data = array();
				$data['op'] = '1';
				$data['uid'] = $uid;
				$data['addnum'] = $addnum;
				$data['total'] = $total;
				$data['time'] = Pw::getTime();
				$mysql->add($data);

				$this->showError('补单成功 O(∩_∩)O~');
			}
		}else if($_GET['act']=='open'){
			if (!$this->loginUser->isExists()) {
				$this->showError('login.not', 'u/login/run');exit;
			}
			$config = Wekit::C('jsjpay');
			$k = Wekit::C('jsjpay', 'credit');
			Wind::import('SRV:credit.bo.PwCreditBo');
			$creditBo = PwCreditBo::getInstance();
			$this->setOutput($creditBo->cType[$k], 'cname');
			$this->setOutput($creditBo->cUnit[$k], 'cunit');
			$this->setOutput($config['bili'], 'bili');
			$this->setOutput($config['min'], 'min');
		}else if($_GET['act']=='post'){
			if (!$this->loginUser->isExists()) {
				$this->showError('login.not', 'u/login/run');exit;
			}
			$config = Wekit::C('jsjpay');
			if(!$config['apiid'] || !$config['apikey'] || !$config['bili'] || !$config['credit']) {
				$this->showError('插件配置不全，请联系管理员。');
			}	
			if(!$_POST['num'] || !is_numeric($_POST['num']) || $_POST['num']<$config['min'] || 
$_POST['num']<=0) {
				$this->showError('充值数量填写错误！');
			}

			$uid = $this->loginUser->uid;
			$name = !$config['name'] ? '网站会员充值' : $config['name'];
			$more = $this->loginUser->username;
			$total = ceil($_POST['num']/$config['bili']);	
			$apiid = $config['apiid'];
			$apikey = md5($config['apikey']);
			$addnum = 'alipw'.$config['apiid'].$uid.Pw::getTime();
			$showurl = ''.PUBLIC_URL.'/';

			$data = array();
			$data['op'] = '9';
			$data['uid'] = $uid;
			$data['addnum'] = $addnum;
			$data['total'] = $total;
			$data['time'] = Pw::getTime();
			$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
			$mysql->add($data);

			echo "
			<form name='form1' action='http://api.web567.net/plugin.php?id=add:alipay_pw' method='POST'>
			<input type='hidden' name='name' value='".$name."'>
			<input type='hidden' name='more' value='".$more."'>
			<input type='hidden' name='uid' value='".$uid."'>
			<input type='hidden' name='total' value='".$total."'>
			<input type='hidden' name='apiid' value='".$apiid."'>
			<input type='hidden' name='apikey' value='".$apikey."'>
			<input type='hidden' name='showurl' value='".$showurl."'>
			<input type='hidden' name='charset' value='utf8'>
			<input type='hidden' name='addnum' value='".$addnum."'>
			</form>
			<script>window.onload=function(){document.form1.submit();}</script> 
			";
		}else{
			header("HTTP/1.1 301 Moved Permanently");
			header("location:index.php?m=profile&c=extends&_left=credit&_tab=jsjpay");
			exit; 
		}
	}

	public function returnAction() {
		$config = Wekit::C('jsjpay');
		$k = Wekit::C('jsjpay', 'credit');
		$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();

		$uid = !empty($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];
		$addnum = !empty($_POST['addnum']) ? $_POST['addnum'] : $_GET['addnum'];
		$total = !empty($_POST['total']) ? $_POST['total'] : $_GET['total'];
		if(!$uid || !$addnum || !$total){
			header('location:'.PUBLIC_URL.'/index.php?m=profile&c=extends&_left=credit&_tab=jsjpay');exit;
		}
		if(!empty($_POST['apikey'])) {
			if($_POST['apikey']!=md5($config['apikey'])){
				header('location:'.PUBLIC_URL.'/index.php?m=profile&c=extends&_left=credit&_tab=jsjpay');exit;
			}
		}
		if(!empty($_GET['apikey'])) {
			if($_GET['apikey']!=md5($config['apikey'].$addnum)){
				header('location:'.PUBLIC_URL.'/index.php?m=profile&c=extends&_left=credit&_tab=jsjpay');exit;
			}
		}

		$cnums =  $total*$config['bili'];
		$cname = $creditBo->cType[$k];		
		$num = $mysql->getsaddnum($addnum);
		if(!$num){
            		$msgname = '<font color=green>充值成功！</font>';
			$msgmore = '<font color=green>你已成功充值 <b>'.$cnums.$cname.'</b></font>    <a href=index.php?m=profile&c=extends&_left=credit&_tab=jsjpay>点此查看记录</a>';
			$creditBo->set($uid, $k, +$cnums);
			Wekit::load('message.srv.PwNoticeService')->sendDefaultNotice($uid,$msgmore,$msgname);				
			$data = array();
			$data['op'] = '1';
			$data['uid'] = $uid;
			$data['addnum'] = $addnum;
			$data['total'] = $total;
			$data['time'] = Pw::getTime();
			$mysql->add($data);
		}else if($num['op']=='9'){
            		$msgname = '<font color=green>充值成功！</font>';
			$msgmore = '<font color=green>你已成功充值 <b>'.$cnums.$cname.'</b></font>    <a href=index.php?m=profile&c=extends&_left=credit&_tab=jsjpay>点此查看记录</a>';
			$creditBo->set($num['uid'], $k, +$cnums);
			Wekit::load('message.srv.PwNoticeService')->sendDefaultNotice($num['uid'],$msgmore,$msgname);				
			$id = intval($num['id']);				
			$data = array();
			$data['op'] = '1';
			$data['total'] = $total;
			$data['time'] = Pw::getTime();
			$mysql->update($id,$data);
		}
		$this->setOutput($cname, 'cname');
		$this->setOutput($cnums, 'cnums');
		$this->setOutput($this->loginUser->username, 'username');                   
	}

}

?>