<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Policys;

class Policy extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);


            $list = Policys::page($page,$limit)->field('id,name,type,max_size')->select()->each(function($item){
                $item['max_size'] = countSize($item['max_size']);
                $item['type'] = PolicyType($item['type']);
                $item['file_num'] = 0;
                $item['store_num'] = '0B';
                return $item;
            });

            $count = Policys::count();

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

    public function add(){
        if($this->request->isPost()){
            $this->callModelMethods('Policys','addPolicy',input('post.'));
            $this->returnSuccess();
        }
        return $this->fetch();
    }

    public function edit(){
        $id = input('get.id');
        $info = Policys::get($id);

        if(empty($info)){
            $this->error('存储策略数据不存在');
        }

        if($this->request->isPost()){
            $this->callModelMethods('Policys','editPolicy',$id,input('post.'));
            $this->returnSuccess();
        }

        $info['config'] = json_decode($info['config'],true);

        $this->assign('info',$info);
        return $this->fetch();
    }

    public function delete(){
        $id = input('get.id');
        $info = Policys::get($id);

        if(empty($info)){
            $this->returnError('存储策略不存在');
        }

        Policys::where('id',$id)->delete();

        $this->returnSuccess('删除成功');
    }

}