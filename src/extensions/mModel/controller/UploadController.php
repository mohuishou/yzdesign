<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/1/23
 * Time: 19:20
 */
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:extensions.mModel.admin.FileUpload');
Wind::import('SRC:extensions.mModel.admin.ImageFilter');
Wind::import('SRC:extensions.mModel.admin.tJsonReturn');

/**
 * 图片上传控制器
 * Class UploadController
 * @author mohuishou <1@lailin.xyz>
 */
class UploadController extends PwBaseController {
    use tJsonReturn;

    public function uploadAction(){
        if(isset($_FILES["img"])){
            $this->pictureAction();
        }else if (isset($_FILES["models"])){
            $this->fileAction();
        }
        $this->setTemplate('');
    }

    public function fileAction(){
        //文件上传配置
        $dir=$this->getDir();
        $upload = new FileUpload();
        $upload->set('path',$dir."file/")
            ->set('maxsize',20*1024*1024)
            ->set("allowtype", array("zip", "7z", "rar"))
            ->set("israndname", true);
        if($upload -> upload("models")){
            $filename=$upload->getFileName();
            $path='attachment/'.date('ym').'/m-model/file/'.$filename;
            $data['path']=$path;
            $data["uid"]=$this->loginUser->uid;
            $id=$this->fileDao()->add($data);
            $this->success("上传成功！",['filename'=>$filename,'id'=>$id]);
        }else{
            $msg=$upload->getErrorMsg();
            $this->error($msg);
        }
        $this->setTemplate('');
    }

    //图片上传
    public function pictureAction(){
        if (count($_FILES["img"])>5){
            $this->error("最多只能上传五张图片！");

        }

        //图片上传配置
        $dir=$this->getDir()."pic/";
        $upload = new FileUpload();
        $upload->set('path',$dir)
            ->set('maxsize',10*1024*1024*5)
            ->set("allowtype", array("gif", "png", "jpg","jpeg"))
            ->set("israndname", true);

        //图片上传处理
        if($upload -> upload("img")){
            $filename=$upload->getFileName();
            if (is_array($filename)){
                foreach ($filename as $val){
                    $file_path[]='attachment/'.date('ym').'/m-model/pic/'.$val;
                }
            }else{
                $file_path[]='attachment/'.date('ym').'/m-model/pic/'.$filename;
            }
            $res=$this->picture($file_path);
            $this->success("上传成功！",$res);
        }else{
            $msg=$upload->getErrorMsg();
            $this->error($msg);
        }
        $this->setTemplate('');
    }


    /**
     * 图片处理
     * 添加水印
     * 保存路径到数据库
     * @author mohuishou<1@lailin.xyz>
     * @param $paths
     * @return string $ids 返回图片的id
     */
    public function picture($paths){
        $font_dir=dirname(__DIR__)."/res/font/";
        $font=$font_dir."wqwm.ttf";
        $return_data=[];
        $ids='';
        if (!is_array($paths)){
            return false;
        }
        foreach ($paths as $k => $v){
            $data['path']=$v;
            $data["uid"]=$this->loginUser->uid;
            $id=$this->pictureDao()->add($data);
            $ids .=$id.",";
            $return_data['path'][]=$v;
            $text="编号：{$id},亚洲室内设计";
            $job=[
                'scaling'=>['size'=>"800,615"],
                'imagetext'=>['text'=>$text,'fontsize'=>'17','fontfamily'=>$font]
            ];
            $image=new ImageFilter($v,$job,$v);
            $image->outimage();
        }
        //删除多余的逗号
        $ids=rtrim($ids, ",");
        $return_data['pictures']=$ids;
        return $return_data;

    }

    private function pictureDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.PictureDao');
    }

    private function fileDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.FileDao');
    }

    private function getDir(){
        $src_dir=dirname(Wind::getRealPath('SRC:a'));
        $root_dir=dirname($src_dir);
        $file_dir=$root_dir."/attachment";
        return $file_dir.'/'.date('ym').'/m-model/';
    }
}

