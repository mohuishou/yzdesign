<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/3/4
 * Time: 15:26
 */
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');

class CategoryController extends AdminBaseController {

    public function beforeAction($handlerAdapter) {
        parent::beforeAction($handlerAdapter);
    }

    public function run() {
    }

    public function addAction(){
        $datas=$this->getInput(["name","tid","pid"],"POST",true);
        $token=$this->getInput("csrf_token");
        if (!$token){
            $type_data=$this->typeDao()->getList();
            $cate_data=$this->cateDao()->getList("pid=0");
            $this->setOutput($type_data,"type_data");
            $this->setOutput($cate_data,"cate_data");
            $this->setTemplate("category_add");
        }else{
            $this->setTemplate("");
            $this->valid($datas,[
                "name"=>"empty"
            ]);
            if($this->cateDao()->add($datas)){
                return $this->success("新增成功");
            }else{
                return $this->error("新增失败");
            }
        }
    }

    public function deleteAction(){

    }

    public function editAction(){

    }

    private function typeDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.TypeDao');
    }

    private function cateDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.CategoryDao');
    }
}