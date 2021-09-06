<?php

namespace app\common\controller;

use think\Controller;
use think\exception\HttpResponseException;
use think\Response;

class Admin extends Controller
{

    protected $middleware = ['AdminLoginCheck'];

    protected function callModelMethods($model,$methods,...$args){
        $class_name = 'app\common\model\\'.$model;
        $class = new $class_name;
        try {
            call_user_func_array([$class,$methods],$args);
        }catch (\Throwable $e){
            $this->returnError($e->getMessage(),505);
        }
    }

    protected function returnSuccess($msg = '操作成功',$data = []){
        $result = [
            'code' => 200,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $type     = 'json';
        $response = Response::create($result, $type)->header([]);
        throw new HttpResponseException($response);
    }

    protected function returnSuccessLayTable($count,$data = []){
        $result = [
            'code' => 0,
            'msg'  => '加载成功',
            'count' => $count,
            'data' => $data,
        ];

        $type     = 'json';
        $response = Response::create($result, $type)->header([]);
        throw new HttpResponseException($response);
    }

    protected function returnError($msg = '操作失败，系统错误',$code = 502,$data = []){
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];
        $type     = 'json';
        $response = Response::create($result, $type)->header([]);
        throw new HttpResponseException($response);
    }

    protected function getQueryMap($methods,$kw = [],$ql = []){
        $map = [];
        foreach ($ql as $item){
            $value = input($methods.'.'.$item);
            if(!empty($value)){
                $map[$item] = $value;
            }
        }
        foreach ($kw as $item){
            $value = input($methods.'.'.$item);
            if(!empty($value)){
                $map[$item] = $value;
            }
        }
        return $map;
    }

}