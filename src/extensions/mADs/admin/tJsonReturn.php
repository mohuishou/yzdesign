<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/1/23
 * Time: 22:29
 */

trait tJsonReturn {
    public function success($msg='',$data=[]){
        $this->jsonReturn([
            'status'=>1,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    public function error($msg='',$data=[]){
        $this->jsonReturn([
            'status'=>0,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    public function jsonReturn($arr){
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
}