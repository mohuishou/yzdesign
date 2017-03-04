<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/3/4
 * Time: 14:18
 */
Wind::import('SRC:extensions.mModel.admin.Validate');
/**
 * Class tValidate
 * 用于验证表单字段值
 * @author mohuishou <1@lailin.xyz>
 */
trait tValidate{

    public function valid($array,$rules){
        $validate=new Validate();
        if (!is_array($rules)){
            foreach ($array as $v){
                $res=$validate->valid($v,$rules);
                if($res){
                    $this->error($res);
                    exit();
                }
            }
            return true;
        }

        foreach ($rules as $k=>$v){
            $res=$validate->valid($array[$k],$v);
            if($res){
                $this->error($res);
                exit();
            }
        }

        return true;
    }


}