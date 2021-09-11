<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Groups;
use app\common\model\Policys;
use app\common\model\Users;

class Group extends Admin
{

    public function index(){

        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);

            $list = Groups::page($page,$limit)->select()->each(function($item){
                $item['max_storage'] = countSize($item['max_storage']);
                $item['is_sys'] = $item['is_sys'] ? '<span class="layui-badge-rim">系统</span>' : '自定义';
                $item['user_num'] = Users::where('group',$item['id'])->count();
                $item['policy_id'] = Policys::where('id',$item['policy_id'])->value('name');
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
        $policy = Policys::getPolicyAll();
        $this->assign('policy',$policy);
        return $this->fetch();
    }

    public function edit(){
        $id = input('get.id');
        $info = Groups::get($id);

        if(empty($info)){
            $this->error('用户组数据不存在');
        }

        if($this->request->isPost()){
            $this->callModelMethods('Groups','editGroup',$id,input('post.'));
            $this->returnSuccess();
        }

        $policy = Policys::getPolicyAll();
        $this->assign('policy',$policy);
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function delete(){
        $id = input('get.id');
        $info = Groups::get($id);

        if(empty($info)){
            $this->returnError('用户组不存在');
        }

        if($info['is_sys'] == 1){
            $this->returnError('系统用户组，不可删除');
        }

        /**
         * 判断用户组下是否存在用户
         */

        Groups::where('id',$id)->delete();

        $this->returnSuccess('删除成功');
    }

}