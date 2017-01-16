<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('APPS:profile.service.PwProfileExtendsDoBase');

class ThisclassDo extends PwProfileExtendsDoBase {
	
	public function createHtml() {
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
		$config = Wekit::C('jsjpay');
		$k = Wekit::C('jsjpay', 'credit');				
		$act = !empty($_GET['act']) ? $_GET['act'] : 'pay';
		$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');

		$page = intval($_GET['page']) ? intval($_GET['page']):1;
		$perpage = 10;
	        list($offset, $limit) = Pw::page2limit($page, $perpage);
		$time1 = $_POST['time_start'] ? intval(strtotime($_POST['time_start'])) : intval($_GET['time_start']);
		$time2 = $_POST['time_end'] ? intval(strtotime($_POST['time_end'])) : intval($_GET['time_end']);
		if($time1>'0') $wheretime1 = "and `time`>".$time1."";
		if($time2>'0') $wheretime2 = "and `time`<".$time2."";
		$op = $_POST['op'] ? intval($_POST['op']) : intval($_GET['op']);
		if($op>'0') $whereop = "and `op`=".$op."";
		$where = " and `uid`='".Wekit::getLoginUser()->uid."' ".$wheretime1." ".$wheretime2." ".$whereop." ";
		$list = $mysql->getList($where, $limit, $offset);
		$num = $mysql->countByWhere($where);
		$args = array('_left'=>'credit','_tab'=>'jsjpay','time_start'=>$time1,'time_end'=>$time2,'op'=>$op);
		$data = array('list'=>$list,'page'=>$page,'perpage'=>$perpage,'num'=>$num,'args'=>$args);
		$hook = array(
			'bili' => Wekit::C('jsjpay', 'bili'),
			'min' => Wekit::C('jsjpay', 'min'),
			'cname' => $creditBo->cType[$k],
			'cunit' => $creditBo->cUnit[$k],
			'cmyye' => Wekit::getLoginUser()->info['credit'.$k],
		);
		
		if($act=='delete'){
			if($_POST['showid']){
				foreach($_POST['showid'] as $k=>$id){
					$id=intval($id);
					$mysql->delete($id);			
				}
			}
		}
		
		PwHook::template($act, 'EXT:jsjpay.template.pay_run', true, $hook, $data);		
	}
}

class Appjsjpay_profilseDo extends PwBaseHookInjector {
	
	public function run() {
		$config = Wekit::C('jsjpay');
		if (!$config['isopen']) header("location:index.php?m=app&app=jsjpay");
		return new ThisclassDo();
	}
}