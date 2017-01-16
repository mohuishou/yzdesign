<?php
Wind::import('APPS:admin.library.AdminBaseController');

class ListController extends AdminBaseController {
	
 	public function run() {
		$mysql = Wekit::loadDao('EXT:jsjpay.service.dao.App_jsjpayDao');
		$pop = !empty($_POST['op']) ? intval($_POST['op']) : intval($_GET['op']);
		if($pop>'0') $op = "and `op`=".$pop."";
		$pts = !empty($_POST['times']) ? intval(strtotime($_POST['times'])) : intval($_GET['times']);
		if($pts>'0') $ts = "and `time`>".$pts."";
		$pto = !empty($_POST['timeo']) ? intval(strtotime($_POST['timeo'])) : intval($_GET['timeo']);
		if($pto>'0') $to = "and `time`<".$pto."";
		$pid = !empty($_POST['uid']) ? intval($_POST['uid']) : intval($_GET['uid']);
		if($pid>'0') $uid = "and `uid`=".$pid."";
		$addnum = strip_tags($_POST['addnum']);
		$page = intval($_GET['page']) ? intval($_GET['page']):1;
		$perpage = 20;
	        list($offset, $limit) = Pw::page2limit($page, $perpage);
		$args = array('op'=>$pop,'times'=>$pts,'timeo'=>$pto,'uid'=>$pid);
		
		if(empty($addnum)){
			$where = " ".$op." ".$ts." ".$to." ".$uid." ";
			$num = $mysql->countByWhere($where);
			$list = $mysql->getList($where, $limit, $offset);
		}else{
			$list = array();
			$val = $mysql->getsaddnum($addnum);
		}
		$this->setOutput($args, 'args');		
		$this->setOutput($page, 'page');
		$this->setOutput($perpage, 'perpage');
		$this->setOutput($num, 'num');
		$this->setOutput($val, 'val');
		$this->setOutput($list, 'list');
		if($_GET['act']=='delete'){
			if(!empty($_POST['showid'])){
				foreach($_POST['showid'] as $k=>$id){
					$id=intval($id);
					$mysql->delete($id);			
				}
				$this->showMessage('OK');
			}else{
				$this->showError('NOT ID');
			}
		}
	}
}
?>