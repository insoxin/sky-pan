<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Reports;

class Report extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',20);
            $list = Reports::order('create_time desc')
                ->page($page,$limit)
                ->field('id,source_name,source_url,source_username,type,contact,real_ip,status,create_time')
                ->select()->each(function($item){
                    $item['create_time'] = date('Y-m-d H:i',$item['create_time']);
                    return $item;
                });

            $count = Reports::count();

            return json([
               'code' => 0,
               'msg' => '获取成功',
               'data' => $list->toArray(),
               'count' => $count
            ]);
        }
        return $this->fetch();
    }

    public function edit(){
        $id = input('get.id');

        $info = Reports::where('id',$id)->find();

        if(empty($info)){
            $this->error('数据不存在');
        }

        if($this->request->isPost()){

            $status = input('post.status');

            $info['status'] = $status;

            $info->save();

            return json(['code' => 200,'msg' => '处理成功']);
        }

        $this->assign('info',$info);

        return $this->fetch();
    }

    public function delete(){
        $id = input('get.id');

        Reports::where('id',$id)->delete();

        return json(['code' => 200,'msg' => '删除成功']);
    }

}