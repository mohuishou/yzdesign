<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/1/23
 * Time: 19:20
 */
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mADs.admin.FileUpload');
Wind::import('SRC:extensions.mADs.admin.tJsonReturn');
class UploadController extends AdminBaseController {
    use tJsonReturn;

    public function uploadAction(){
        $dir=$this->getDir();
        $upload = new FileUpload();
        $upload->set('path',$dir)
            ->set('maxsize',20*1024*1024)
            ->set("allowtype", array("gif", "png", "jpg","jpeg"))
            ->set("israndname", true);
        if($upload -> upload("img")){
            $filename=$upload->getFileName();
            $file_path='attachment/'.date('ym').'/m-ads/'.$filename;
            $this->success("上传成功！",['path'=>$file_path]);
        }else{
            $msg=$upload->getErrorMsg();
            $this->error($msg);
        }
        $this->setTemplate('');
    }

    private function getDir(){
        $src_dir=dirname(Wind::getRealPath('SRC:a'));
        $root_dir=dirname($src_dir);
        $file_dir=$root_dir."/attachment";
        return $file_dir.'/'.date('ym').'/m-ads/';
    }
}

