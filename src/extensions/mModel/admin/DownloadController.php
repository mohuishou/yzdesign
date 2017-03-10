<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');
Wind::import('SRC:service.user.PwUser');

class DownloadController extends AdminBaseController {
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
	}

    /**
     * 下载记录
     */
	public function run() {
	    $user=new PwUser();
        $page=$this->getInput("page");
        $page || $page=1;
        $perpage=10;
        $datas=$this->downloadDao()->getList(1,$page*$perpage,($page-1)*$perpage);
        foreach ($datas as &$v){
            $v['user']=$user->getUserByUid($v['uid']);
            $v["updated_time"]=date("Y-m-d H:i:s",$v["updated_time"]);
            $v["name"]=$this->getItemById($v["mid"]);
        }
        $this->setOutput($datas,"datas");
        $this->setOutput($datas,"datas");
        $this->setOutput($perpage,"perpage");
        $this->setOutput($page,"curr");
        $this->setOutput(ceil($this->downloadDao()->getCount() / $perpage),"pages");
	}

	public function getItemById($id){
        $item=$this->itemDao()->get($id);
        return $item["name"];
    }

    private function itemDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.ItemDao');
    }


    /**
     * 删除一条下载记录
     */
    public function deleteAction(){
        $this->setTemplate("");
        $id=$this->getInput("id");
        if ($this->downloadDao()->delete($id)){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }


    private function downloadDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.DownloadDao');
    }
}

?>