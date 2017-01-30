<?php
Wind::import('APPS:admin.library.AdminBaseController');
/**
 * 后台访问入口
 *
 * @author 杨周 <yzhou91@aliyun-inc.com> QQ:89652519
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.yhcms.com
 * @package wind
 */

class ManageController extends AdminBaseController {
	public $page = 1;
	public $perpage = 20;

    public function run() {
		$config=Wekit::C('yunqian');
        Wind::import('SRV:credit.bo.PwCreditBo');
        $creditBo = PwCreditBo::getInstance();
		
        $this->setOutput($creditBo, 'creditBo');
		$this->setOutput($config, 'config');
    }
    
	
 	public function doRunAction() {
 		$arr = $this->getInput('config', 'post');
		if($arr[time_sta]>$arr[time_end]){
			$time=(24-$arr[time_sta])+$arr[time_end];
		}else if($arr[time_sta]<$arr[time_end]){
			$time=$arr[time_end]-$arr[time_sta];
		}else if($arr[time_sta]<$arr[time_end]){
			$time='24';
		}
		$time_two=$arr[time_two]*$arr[sum];
		if($time_two>$time){
			$this->showError('签到时间限制有误，请重新填写！');
		}
 		$config = new PwConfigSet('yunqian');
		
		if($arr['isopen_post']){
			if(!$arr['post_fid']) $this->showError('请输入签到版块FID！');
			if(!$arr['post_title']) $this->showError('请输入发帖标题！');
			if(!$arr['post_content']) $this->showError('请输入发帖内容！');
			if(!$arr['post_reply']) $this->showError('请输入回复内容！');
		}
		if($arr['post_fid']){
        	Wind::import('SRV:forum.bo.PwForumBo');
			$forum = new PwForumBo($arr['post_fid']);
			if(!$forum->isForum()) $this->showError('签到版块不存在！');
			if(!$forum->isOpen()) $this->showError('你输入的版块不是开放版块！');
		}
 		foreach($arr as $k => $v){
 			if(!$v)$v=0;
			$config->set($k, $v)->flush();
 		}
		$this->showMessage('设置成功');
 	}
}

?>