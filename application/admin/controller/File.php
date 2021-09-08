<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Stores;

class File extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $map = [];


            $list = Stores::where($map)->page($page,$limit)->select();

            $count = Stores::where($map)->count();

            return json([
                'code' => 0,
                'msg' => '加载成功',
                'count' => $count,
                'data' => $list->toArray()
            ]);
        }else{
            return $this->fetch();
        }
    }


}