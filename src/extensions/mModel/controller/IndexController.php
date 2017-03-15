<?php
defined('WEKIT_VERSION') or exit(403);
/**
* 前台入口
*
* generated by phpwind.com
*/
class IndexController extends PwBaseController {
    
    public function beforeAction($handlerAdapter) {
        parent::beforeAction($handlerAdapter);
    }
    
    public function run() {
        //首先需要模型大的分类
        $type_id=$this->getInput("type_id");
        if (!$type_id){
            $this->showError("请先选择模型类别！");
        }

        //分类信息
        $type=$this->typeDao()->get($type_id);
        $this->setOutput($type,"type");
        
        //查询条件
        $where="tid={$type_id}";
        $cid=$this->getInput("cid");
        if($cid){
            $where .=" AND cid=".$cid;
        }
        
        //排序方式
        $order=$this->getInput("order");
        switch($order){
            case 1:
                $order="updated_time desc";
                break;
            case 2:
                $order="download desc";
                break;
            default:
                $order="updated_time desc";
                break;
        }

        $page=$this->getInput("page");
        $page || $page=1;
        $perpage=10;

        //条目详情
        $items=$this->itemDao()->getList($where,$page*$perpage,($page-1)*$perpage,$order);
        $this->setOutput($items,"items");

        //获取所有的模型类别信息
        $cates=$this->getCate($type_id);
        $this->setOutput($cates,"cates");
    }

    public function detailsAction(){
        //获取id
        $id=$this->getInput("id");
        if(!$id){
            $this->showError("对不起，请先选择条目");
        }        

        //获取item详情
        $data=$this->itemDao()->get($id);
        $this->setOutput($data,"data");
        print_r($data);

        //获取分类信息
        $cate['child']=$this->cateDao()->get($data['cid']);
        $cate['parent']=$this->cateDao()->get($cate['child']['pid']);      
        $this->setOutput($cate,"cate");

        //获取模型分类信息
        $type=$this->typeDao()->get($data['tid']);
        $this->setOutput($type,"type");

        //获取相关性的搜索
        
    }

    //添加浏览量
    public function addLookAction(){

    }

    //添加点赞
    public function addLikeAction(){

    }

    public function downloadAction(){
        
    }
    
    //获取分类信息
    protected function getCate($tid){
        $parent=$this->cateDao()->getList("tid={$tid} AND pid=0");
        $data=[];
        foreach($parent as $v){
            $data['parent']=$v;
            $data['children']=[];
            if($v['id']){
                $data['children']=$this->cateDao()->getList("tid={$tid} AND pid=".$v['id']);
                
            }
        }
        return $data;
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