<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');
Wind::import('SRC:extensions.mModel.admin.tValidate');
/**
 * Class TypeController
 * 设置类型
 * @author mohuishou <1@lailin.xyz>
 */
class TypeController extends AdminBaseController {
    use tJsonReturn,tValidate;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
	}
	
	public function run() {
	    $datas=$this->typeDao()->getList();
        $this->setOutput($datas,"datas");
        $this->setTemplate("type_run");
	}

	public function addAction(){
        $datas=$this->getInput(["name","style","version","img_type","light"],"POST",true);
        $token=$this->getInput("csrf_token");
        if (!$token){
            $this->setTemplate("type_add");
        }else{
            $this->setTemplate("");
            $this->valid($datas,[
                "name"=>"empty"
            ]);
            if($this->typeDao()->add($datas)){
                return $this->success("新增成功");
            }else{
                return $this->error("新增失败");
            }
        }
	}

	public function editAction(){
		$id=$this->getInput("id");
        $token=$this->getInput("csrf_token");
        $datas=$this->getInput(["name","style","version","img_type","light"],"POST",true);
        if (!$token){
            $data=$this->typeDao()->get($id);
            $this->setOutput($data,"data");
            $this->setTemplate("type_edit");
        }else{
            $this->setTemplate("");
            $this->valid($datas,[
                "name"=>"empty"
            ]);
            if($this->typeDao()->update($id,$datas)){
                return $this->success("更新成功");
            }else{
                return $this->error("更新失败");
            }
        }
	}

    public function deleteAction(){
        $this->setTemplate("");
        $id=$this->getInput("id");
        if ($this->typeDao()->delete($id)){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    private function typeDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.TypeDao');
    }
}

?>