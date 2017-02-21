<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mADs.admin.tJsonReturn');
/**
 * 后台访问入口
 *
 * generated by phpwind.com
 */
class ManageController extends AdminBaseController {
    use tJsonReturn;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		//TODO do something before all the action
	}
	
	public function run() {
		$data=$this->_navDao()->getList(20,0);
        $this->setOutput($data,"data");
	}

	public function addAction(){
        $token=$this->getInput("csrf_token");
        if ($token){
            $this->setTemplate("");
            $data['a']=$this->getInput('atag');
            $data['link']=$this->getInput('link');
            $data['style']=$this->getInput('style');
            $data['title']=$this->getInput('title');
            $data['sort']=$this->getInput('sort');
            $data['float_type']=$this->getInput('float_type');
            $res=$this->_navDao()->add($data);
            if($res){
                $this->success("新增成功!",$data);
            }else{
                $this->error("新增失败！");
            }
        }else{
            $this->setTemplate("manage_add");
        }
	}

	public function editAction(){
        $token=$this->getInput("csrf_token");
        $id=$this->getInput("id");
        if ($token){
            $this->setTemplate("");
            $data['a']=$this->getInput('atag');
            $data['link']=$this->getInput('link');
            $data['style']=$this->getInput('style');
            $data['title']=$this->getInput('title');
            $data['sort']=$this->getInput('sort');
            $data['float_type']=$this->getInput('float_type');
            $res=$this->_navDao()->update($id,$data);
            if($res){
                $this->success("更新成功!",$data);
            }else{
                $this->error("更新失败！");
            }
        }else{
            $data=$this->_navDao()->get($id);
            if($data["style"]){
                $style=[];
                $temp=explode(";",$data["style"]);
                foreach ($temp as $v){
                    $tmp=explode(":",$v);
                    if(count($tmp)==2){
                        $style[$tmp[0]]=trim($tmp[1]);
                    }
                }
                $data["style"]=$style;
            }
            $this->setOutput($data,"data");
            $this->setTemplate("manage_edit");
        }
	}

    public function deleteAction(){
        $id=$this->getInput('id');
        $res=$this->_navDao()->delete($id);
        if($res){
            $this->success("删除成功!");
        }else{
            $this->error("删除失败！");
        }
        $this->setTemplate("");
    }

    protected function _navDao(){
        return Wekit::load('SRC:extensions.mTopNav.service.dao.App_MTopNav_MTopNavDao');
    }
}


?>