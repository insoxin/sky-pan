<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Groups;

class Group extends Admin
{

    public function index(){

        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);

            $list = Groups::page($page,$limit)->select()->each(function($item){
                $item['max_storage'] = countSize($item['max_storage']);
                return $item;
            });
            $count = Groups::count();
            $this->returnSuccessLayTable($count,$list->toArray());
        }

        return $this->fetch();
    }

    public function add(){
        if($this->request->isPost()){
            $this->callModelMethods('Groups','addGroup',input('post.'));
            $this->returnSuccess();
        }
        return $this->fetch();
    }

    public function edit(){

    }

    public function delete(){

    }

}