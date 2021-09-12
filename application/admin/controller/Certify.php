<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Certify as CertifyModel;
use app\common\model\Users;

class Certify extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',20);
            $list = CertifyModel::order('create_time desc')
                ->page($page,$limit)
                ->field('id,uid,name,idcard,status,create_time')
                ->select()->each(function($item){
                    $item['create_time'] = date('Y-m-d H:i',$item['create_time']);
                    $item['uid'] = Users::where('id',$item['uid'])->value('username');
                    return $item;
                });

            $count = CertifyModel::count();

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

        $info = CertifyModel::where('id',$id)->find();

        if(empty($info)){
            return json(['code' => 502,'msg' => '数据不存在']);
        }

        $info['status'] = 1;

        $info->save();

        Users::where('id',$info['uid'])->update(['is_auth' => 1]);

        return json(['code' => 200,'msg' => '处理成功']);
    }

    public function delete(){
        $id = input('get.id');

        CertifyModel::where('id',$id)->delete();

        return json(['code' => 200,'msg' => '删除成功']);
    }

}