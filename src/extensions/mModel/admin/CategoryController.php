<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/3/4
 * Time: 15:26
 */
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');
Wind::import('SRC:extensions.mModel.admin.tValidate');

/**
 * 模型分类控制
 */
class CategoryController extends AdminBaseController {
    use tValidate,tJsonReturn;

    public function beforeAction($handlerAdapter) {
        parent::beforeAction($handlerAdapter);
    }

    public function run() {
        $perpage=10;
        $page=$this->getInput("page");
        $page || $page=1;
        $datas=$this->cateDao()->getList(1,$page*$perpage,($page-1)*$perpage);
        $this->setOutput($datas,"datas");
        $this->setOutput($perpage,"perpage");
        $this->setOutput($page,"curr");
        $this->setOutput(ceil($this->cateDao()->getCount() / $perpage),"pages");
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
                "name"=>"empty",
                "tid"=>"empty"
            ]);
            if($this->cateDao()->add($datas)){
                return $this->success("新增成功");
            }else{
                return $this->error("新增失败");
            }
        }
    }

    public function deleteAction(){
        $this->setTemplate("");
        $id=$this->getInput("id");
        $data=$this->cateDao()->getList("pid=".$id);
        if(!empty($data)){
            $this->error("该分类下还有子类别，请先删除！");
            return;
        }
        if ($this->cateDao()->delete($id)){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    public function editAction(){
        $id=$this->getInput("id");
        $datas=$this->getInput(["name","tid","pid"],"POST",true);
        $token=$this->getInput("csrf_token");
        if (!$token){
            $data=$this->cateDao()->get($id);
            $type_data=$this->typeDao()->getList();
            $cate_data=$this->cateDao()->getList("pid=0");
            $this->setOutput($data,"data");
            $this->setOutput($type_data,"type_data");
            $this->setOutput($cate_data,"cate_data");
            $this->setTemplate("category_edit");
        }else{
            if ($datas['pid']){
                $data=$this->cateDao()->getList("pid=".$id);
                if(!empty($data)){
                    $this->error("该顶级分类下还有子类别，请先删除！");
                    return;
                }
            }
            $this->setTemplate("");
            $this->valid($datas,[
                "name"=>"empty",
                "tid"=>"empty"
            ]);
            if($this->cateDao()->update($id,$datas)){
                return $this->success("更新成功");
            }else{
                return $this->error("更新失败");
            }
        }
    }

    public function getAction(){
        $pid=$this->getInput("pid");
        $pid || $pid=0;
        $data=$this->cateDao()->getList("pid=".$pid);
        if(!empty($data)){
            $this->success("获取成功！",$data);
        }else{
           $this->success("改分类无子分类，请另行选择！",$data); 
        }
        $this->setTemplate("");
    }

    private function typeDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.TypeDao');
    }

    private function cateDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.CategoryDao');
    }
}