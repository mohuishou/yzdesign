<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('SRC:service.user.PwUser');
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');

/**
* 前台入口
*
* generated by phpwind.com
*/
class IndexController extends PwBaseController {
    use tJsonReturn;
    
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
    $data["is_liked"]=0;
    
    if($this->loginUser->uid){
        $like=$this->likeDao()->getByLast($id,$this->loginUser->uid);
        if(isset($like)&&!empty($like)){
            $data["is_liked"]=1;
        }
    }
    
    
    $this->setOutput($data,"data");
    
    //获取分类信息
    $cate['child']=$this->cateDao()->get($data['cid']);
    $cate['parent']=$this->cateDao()->get($cate['child']['pid']);
    $this->setOutput($cate,"cate");
    
    //获取模型分类信息
    $type=$this->typeDao()->get($data['tid']);
    $this->setOutput($type,"type");
    
    //获取用户信息
    $user_model=new PwUser();
    $user=$user_model->getUserByUid($data['uid']);
    $this->setOutput($user['username'],"username");
    
    //获取相关性的搜索
    //获取同一子分类的数据
    $about=$this->itemDao()->getList("cid={$data['cid']}",4);
    $this->itemDao()->addLook($id);
    $this->addLook($id);
    $this->setOutput($about,"about");
    
    
}



//添加点赞
public function addLikeAction(){
    if(!$this->loginUser->uid){
        $this->showError("请先登录！");
    }
    $id=$this->getInput("id");
    if(!$id){
        $this->showError("请先选择文件");
    }
    $this->setTemplate("");
    $like=$this->likeDao()->getByLast($id,$this->loginUser->uid);
    if(isset($like)&&!empty($like)){
        $this->error("您已经点过赞了！");
        return;
    }
    $data['mid']=$id;
    $data['uid']=$this->loginUser->uid;
    $this->likeDao()->add($data);
    $this->itemDao()->addLike($id);
    $this->success("点赞成功！");
}

public function downloadAction(){
    if(!$this->loginUser->uid){
        $this->showError("请先登录！");
    }
    $id=$this->getInput("id");
    if(!$id){
        $this->showError("请先选择文件");
    }
    
    //获取信息
    $item=$this->itemDao()->get($id);
    if(!isset($item)||empty($item)){
        $this->showError("文件选择错误");
    }
    
    //是否为作者本人
    if($this->loginUser->uid==$item['uid']){
        $this->file($item['file']);
        return;
    }
    
    //是否在重复下载的免费期限之内
    $download=$this->downloadDao()->getByLast($id,$this->loginUser->uid);
    if(!empty($download[0])){
        $download=$download[0];
        if((time()-$download['created_time'])<3600*24*30){
            $this->file($item['file']);
            return;
        }
    }
    
    
    //获取扣费信息
    $type=$this->typeDao()->get($item['tid']);
    $pay_id=$type['pay_id'];
    $pay_list_name="credit".$pay_id;
    
    //获取下载用户信息
    $duser=$this->userDataDao()->getUserByUid($this->loginUser->uid);
    if($duser[$pay_list_name]<$item['price']){
        $this->showError("对不起，您的{$type['pay_name']}不足，请先充值！需要{$item['price']}，拥有{$duser[$pay_list_name]}");
    }
    
    //扣费
    $money=$duser[$pay_list_name]-$item['price'];
    $this->userDataDao()->editUser($this->loginUser->uid,[$pay_list_name=>$money]);
    
    //给上传用户添加费用
    $upuser=$this->userDataDao()->getUserByUid($item['uid']);
    $money=$upuser[$pay_list_name]+$item['price'];
    $this->userDataDao()->editUser($item['uid'],[$pay_list_name=>$money]);
    
    //添加下载信息
    $downloadData['uid']=$this->loginUser->uid;
    $downloadData['tid']=$item['tid'];
    $downloadData['mid']=$item['id'];
    $this->downloadDao()->add($downloadData);
    
    //添加下载数目
    $this->itemDao()->addDownload($id);
    
    $this->file($item['file']);
}

protected function file($fid){
    $this->setTemplate("");
    //获取文件地址
    $file=$this->fileDao()->get($fid);
    header("location: ".$file['path']);
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




private function fileDao(){
    return Wekit::load('SRC:extensions.mModel.service.dao.FileDao');
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

private function likeDao(){
    return Wekit::load('SRC:extensions.mModel.service.dao.LikeDao');
}

private function userDataDao(){
    return Wekit::load('SRC:service.user.dao.PwUserDataDao');
}
}

?>