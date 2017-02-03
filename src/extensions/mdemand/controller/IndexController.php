<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('SRC:extensions.mADs.admin.tJsonReturn');

/**
 * Class IndexController
 * @author mohuishou <1@lailin.xyz>
 */
class IndexController extends PwBaseController {
    use tJsonReturn;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		//TODO do something before all the action
	}
	
	public function run() {
		//获取最近20条数据
        $data=$this->_mdemandDao()->getNew();
        $this->setOutput($data,'data');
	}

	public function addAction(){
        $this->setTemplate("");
        $data['name']=$this->getInput('name');
        $data['phone']=$this->getInput('phone');
        $data['qq']=$this->getInput('qq');
        $data['category']=$this->getInput('category');
        $data['detail']=$this->getInput('detail');
        $required=[
            "name","phone","category","qq","detail"
        ];
        foreach ($required as  $v){
            if(!isset($data[$v])||empty($data[$v])){
                $this->error("{$v} 必须！");
                return false;
            }
        }

        $res=$this->_mdemandDao()->add($data);

        if ($res){
            $this->success("设计需求提交成功！");
        }else{
            $this->error("设计需求提交失败！");
        }

        return true;
    }

    private function _mdemandDao(){
        return Wekit::load('SRC:extensions.mdemand.service.dao.App_Mdemand_MdemandDao');
    }
}

?>