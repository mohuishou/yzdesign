<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');
Wind::import('SRC:extensions.mModel.admin.tValidate');
/**
* 前台入口
*
* generated by phpwind.com
*/
class ModelController extends PwBaseController {
    use tJsonReturn,tValidate;
    
    protected $uid;
    
    public function beforeAction($handlerAdapter) {
        parent::beforeAction($handlerAdapter);
        //判断是否登录
        if(!$this->loginUser->uid) {
            $this->showError('请登录后查看','u/login/run');
        }else{
            $this->uid=$this->loginUser->uid;
        }
        
    }
    
    public function run() {
        $this->addAction();
    }
    
    //编辑页面
    public function editAction(){
        $type_id=$this->getInput("type_id");
        $types=$this->typeDao()->getList("admin_only=0");
        if (!$type_id){
            $this->showError("请先选择模型类别！");
        }
        $this->setOutput($type_id,"type_id");
        
        $id=$this->getInput("id");
        $token=$this->getInput("csrf_token");
        if (!$token){
            //没有提交数据返回所有分类
            $this->setOutput($types,"types");
            
            //已有的数据
            $data=$this->itemDao()->get($id);
            
            //获取所属类型以及相关设置
            $type=$this->typeDao()->get($type_id);
            $tmp=["style","version","img_type","light"];
            foreach ($type as $k => $v){
                if (in_array($k,$tmp)){
                    if(!empty($v))
                    $this->setOutput(explode(",",$v),$k);
                }
            }
            $this->setOutput($type,"type");
            
            //顶级分类列表
            $cate=$this->cateDao()->getList("pid=0 AND tid=".$type_id);
            $this->setOutput($cate,"cate");
            
            //顶级分类信息
            $category=$this->cateDao()->get($data['cid']);
            $data["pid"]=$category['pid'];
            
            //二级分类列表
            $cate2=$this->cateDao()->getList("pid=".$data["pid"]);
            $this->setOutput($cate2,"cate2");
            
            $this->setOutput($data,"data");
            $this->setTemplate("manage_edit");
        }else{
            $this->setTemplate("");
            $field=['cid','name','style','version','img_type','light','price','description'];
            $data=$this->getInput($field,"POST",true);
            if($this->itemDao()->update($id,$data)){
                return $this->success("更新成功");
            }else{
                return $this->error("更新失败");
            }
        }
    }
    
    public function addAction(){
        $type_id=$this->getInput("type_id");
        if (!$type_id){
            $type_id=$types[0]['id'];
        }
        
        $types=$this->typeDao()->getList("admin_only=0");
        //获取分类信息，如果没有指定分类，默认为第一个
        if (!$type_id){
            $type_id=$types[0]['id'];
        }
        $this->setOutput($type_id,"type_id");
        
        //判断是否提交了数据
        $token=$this->getInput("csrf_token");
        if(!$token){
            //没有提交数据返回所有分类
            $this->setOutput($types,"types");
            
            //当前选中分类信息详情
            $type=$this->typeDao()->get($type_id);
            $tmp=["style","version","img_type","light"];
            foreach ($type as $k => $v){
                if (in_array($k,$tmp)){
                    if(!empty($v))
                    $this->setOutput(explode(",",$v),$k);
                }
            }
            $this->setOutput($type,"type");
            
            //获取该分类下的顶级分类信息
            $cate=$this->cateDao()->getList("pid=0 AND tid=".$type_id);
            $this->setOutput($cate,"cate");
            
            //设置前台模板
            $this->setTemplate("model_add");
        }else{
            $this->setTemplate("");
            $field=['cid','name','style','version','pictures','img_type','light','price','file','description'];
            $data=$this->getInput($field,"POST",true);
            $data['uid']=$this->loginUser->uid;
            $data['tid']=$type_id;
            if($this->itemDao()->add($data)){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
        }
    }
    
    //列表展示页面
    public function showAction(){
        $type_id=$this->getInput("type_id");
        if (!$type_id){
            $this->showError("请先选择模型类别！");
        }
        $types=$this->typeDao()->getList("admin_only=0");
        $this->setOutput($types,"types");
        $page=$this->getInput("page");
        $this->setOutput($type_id,"type_id");
        $page || $page=1;
        $perpage=10;
        $datas=$this->itemDao()->getList("tid={$type_id} AND uid={$this->uid}",$page*$perpage,($page-1)*$perpage);
        $type=$this->typeDao()->get($type_id);
        $this->setOutput($datas,"datas");
        $this->setOutput($datas,"datas");
        $this->setOutput($perpage,"perpage");
        $this->setOutput($page,"curr");
        $this->setOutput(ceil($this->cateDao()->getCount() / $perpage),"pages");
    }
    
    //删除
    public function deleteAction(){
        $this->setTemplate("");
        $id=$this->getInput("id");
        if ($this->itemDao()->delete($id)){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    
    //下载记录
    public function downloadAction(){
        $type_id=$this->getInput("type_id");
        if (!$type_id){
            $this->showError("请先选择模型类别！");
        }
        $this->setOutput($type_id,"type_id");
        $types=$this->typeDao()->getList("admin_only=0");
        $page=$this->getInput("page");
        $page || $page=1;
        $perpage=10;
        $datas=$this->downloadDao()->getList("uid={$this->uid} AND tid={$type_id}",$page*$perpage,($page-1)*$perpage);
        foreach ($datas as &$v){
            $v['user']=$this->loginUser->username;
            $v["updated_time"]=date("Y-m-d H:i:s",$v["updated_time"]);
            $v["name"]=$this->getItemById($v["mid"]);
        }
        $this->setOutput($types,"types");
        $this->setOutput($datas,"datas");
        $this->setOutput($perpage,"perpage");
        $this->setOutput($page,"curr");
        $this->setOutput(ceil($this->downloadDao()->getCount() / $perpage),"pages");
    }
    
    public function getItemById($id){
        $item=$this->itemDao()->get($id);
        return $item["name"];
    }
    
    
    
    private function typeDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.TypeDao');
    }
    
    private function cateDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.CategoryDao');
    }
    
    private function itemDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.ItemDao');
    }
    
    private function downloadDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.DownloadDao');
    }
}

?>