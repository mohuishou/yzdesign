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

class LevelController extends AdminBaseController {
	public $page = 1;
	public $perpage = 20;

	//等级信息
	public function run() {
	$page = intval($this->getInput('page'));
        $this->page = $page < 1 ? 1 : $page;
        list($offset, $limit) = Pw::page2limit($this->page, $this->perpage);
        $total = $this->_getYyunQianLevelDs()->count();
        $dj_list = $total ? $this->_getYyunQianLevelDs()->getListByWhere('', $limit, $offset) : array();
		$this->setOutput($total, 'total');
        $this->setOutput($dj_list, 'dj_list');
		$this->setOutput($this->page, 'page');
		$this->setOutput($this->perpage, 'perpage');
		$this->setTemplate('level_run');
 	}
    public function doAddAction() {
   		$hits = intval($this->getInput('hits', 'post'));
		$dj = intval($this->getInput('dj', 'post'));
		$title = $this->getInput('title', 'post');
		if($title==''){
			$this->showError('请填写等级名称');
        }	
		if($dj<1 || $dj==''){
			$this->showError('等级数填写有误');
        }
		if($hits<1 || $hits==''){
			$this->showError('需要次数填写有误');
        }
    	$this->_getYyunQianLevelDs()->addLevel($title,$dj,$hits);
		$this->showMessage('添加等级成功');
    }
	//删除等级信息
	public function delAction() {
		$id = intval($this->getInput('dj'));
    	$total = $this->_getYyunQianLevelDs()->delete($id);
		$this->showMessage('删除成功');
 	}
	 //修改等级
    public function doEditAction() {
        $data = $this->getInput('data', 'post');
		foreach($data as $dj=>$v){
			$this->_getYyunQianLevelDs()->edit($v['title'], $v['dj'], $v['hits']);	
		}
		$this->showMessage('修改等级成功');	
    }
	
	private function _getYyunQianLevelDs() {
        return Wekit::load('SRC:extensions.yunqian.service.App_YunQianLevel');
    }
}

?>