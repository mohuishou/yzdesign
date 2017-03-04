<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/3/4
 * Time: 16:00
 */

class Helper{

    public function typeName($id){
        $type=$this->typeDao()->get($id);
        echo  $type["name"];
    }

    public function cateName($id){
        if ($id==0){
            echo "顶级类别";
            return;
        }
        $cate=$this->cateDao()->get($id);
        echo $cate["name"];
    }



    private function typeDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.TypeDao');
    }

    private function cateDao(){
        return Wekit::load('SRC:extensions.mModel.service.dao.CategoryDao');
    }
}